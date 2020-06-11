<?php

namespace Nubesys\Auth;

use Nubesys\Core\Common;

//DATA SOURCE
use Nubesys\Data\DataSource\DataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Objects as ObjectsDataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Table as TableDataSource;

class User extends Common {

    public function __construct($p_di)
    {
        parent::__construct($p_di);
    }

    public function loginUser($p_login, $p_password){

        $result                     = false;

        $tableDataSource            = new TableDataSource($this->getDI());

        $objectsDataSourceOptions                  = array();
        $objectsDataSourceOptions['model']         = "usuario";

        $objectsDataSource          = new ObjectsDataSource($this->getDI(), $objectsDataSourceOptions);

        $queryResult                = $tableDataSource->rawQuery("SELECT _id FROM nosvamoos.usuario_objects WHERE JSON_EXTRACT(objData, '$.login') = '" . $p_login . "'");
        
        if(\is_array($queryResult)){

            $userId                 = $queryResult[0]['_id'];

            $userData               = $objectsDataSource->getData($userId);
            
            if($userData['password'] == $p_password){
                
                if(isset($userData['roles'])){

                    $userRolesData                  = array();
        
                    foreach($userData['roles'] as $role){
        
                        $userRolesData[$role]       = $this->getUserRoleData($role);
                    }
        
                    $userData['roles']              = $userRolesData;
                }

                if(isset($userData['role'])){

                    $userData['role']               = $this->getUserRoleData($userData['role']);
                }

                $result                             = array();
                $result['id']                       = $userData['_id'];
                $result['nombre']                   = $userData['nombre'];
                $result['apellido']                 = $userData['apellido'];
                $result['login']                    = $userData['login'];
                $result['password_reset']           = $userData['password_reset'];
                $result['avatar']                   = (isset($userData['avatar'])) ? $userData['avatar'] : "avatar_noavatar";
                $result['genero']                   = $userData['genero'];
                $result['role']                     = $userData['role'];
                $result['roles']                    = $userData['roles'];
            }
        }
        
        return $result;
    }

    private function getUserRoleData($p_role){

        $dataSourceOptions                  = array();
        $dataSourceOptions['model']         = "roles";

        $dataSource                         = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));

        $roleData                           = $dataSource->getData($p_role);

        if(isset($roleData['menus'])){

            $rolesMenusData                 = array();
            
            foreach($roleData['menus'] as $menu){

                $rolesMenusData[$menu]      = $this->getRoleMenuData($menu);
            }

            $roleData['menus']              = $rolesMenusData;
        }
        
        $result                             = array();
        $result['id']                       = $roleData['_id'];
        $result['name']                     = $roleData['name'];
        $result['description']              = $roleData['description'];
        $result['privilegios']              = $roleData['privilegios'];
        $result['menus']                    = $roleData['menus'];
        $result['path']                     = $roleData['path'];

        //TODO : PRIVILEGioS

        return $result;
    }

    private function getRoleMenuData($p_menu){

        $dataSourceOptions                  = array();
        $dataSourceOptions['model']         = "menus";

        $dataSource                         = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));

        $menuData                           = $dataSource->getData($p_menu);

        if(isset($menuData['rel_menu_menuitems'])){

            $menuItemsData                  = array();
            
            foreach($menuData['rel_menu_menuitems'] as $menuitem){

                $menuItemsData[$menuitem]   = $this->getMenuItemsData($menuitem);
            }

            $menuData['rel_menu_menuitems'] = $menuItemsData;
        }

        $result                             = array();
        $result['id']                       = $menuData['_id'];
        $result['name']                     = $menuData['name'];
        $result['description']              = $menuData['description'];
        $result['icon']                     = $menuData['icon'];
        $result['items']                    = $menuData['rel_menu_menuitems'];

        return $result;
    }

    private function getMenuItemsData($p_menuitems){

        $dataSourceOptions                  = array();
        $dataSourceOptions['model']         = "menuitems";

        $dataSource                         = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));

        $menuItemData                       = $dataSource->getData($p_menuitems);

        $result                             = array();
        $result['id']                       = $menuItemData['_id'];
        $result['name']                     = $menuItemData['name'];
        $result['description']              = $menuItemData['description'];
        $result['icon']                     = $menuItemData['icon'];
        $result['path']                     = $menuItemData['path'];

        return $result;
    }
}