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

        $queryString                = "";

        $userTables                 = $this->getDI()->get('config')->main->user->tables->toArray();
        
        $tableIndex                 = 0;

        foreach($userTables as $table){
            
            $queryString            .=  "SELECT _id, modId FROM " . $table . " WHERE JSON_EXTRACT(objData, '$.login') = '" . $p_login . "'";

            if($tableIndex < count($userTables) - 1){

                $queryString            .=  " UNION ALL ";
            }else{

                $queryString            .=  ";";
            }

            $tableIndex ++;
        }
        
        $queryResult                = $tableDataSource->rawQuery($queryString);
        
        if(\is_array($queryResult) && count($queryResult) > 0){

            $userId                 = $queryResult[0]['_id'];
            $userModel              = $queryResult[0]['modId'];

            $objectsDataSourceOptions                  = array();
            $objectsDataSourceOptions['model']         = $userModel;

            $objectsDataSource      = new ObjectsDataSource($this->getDI(), $objectsDataSourceOptions);

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
                $result['model']                    = $userModel;
                $result['nombre']                   = $userData['nombre'];
                $result['apellido']                 = $userData['apellido'];
                $result['login']                    = $userData['login'];
                $result['password_reset']           = ($userData['password_reset'] == "1") ? true : false;
                $result['avatar']                   = (isset($userData['avatar'])) ? $userData['avatar'] : "avatar_noavatar";
                $result['genero']                   = $userData['genero'];
                $result['role']                     = $userData['role'];
                $result['roles']                    = $userData['roles'];
            }
        }
        
        return $result;
    }

    private function getUserRoleData($p_role){
        $result                             = false;

        $dataSourceOptions                  = array();
        $dataSourceOptions['model']         = "roles";

        $dataSource                         = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));
        
        $roleData                           = $dataSource->getData($p_role);
        
        if(isset($roleData['_id'])){

            if(isset($roleData['menus'])){

                $rolesMenusData                 = array();
                
                foreach($roleData['menus'] as $menu){
    
                    $rolesMenusData[$menu]      = $this->getRoleMenuData($menu);
                }
                
                $roleData['menus']              = $rolesMenusData;
            }
            
            $result['id']                       = $roleData['_id'];
            $result['name']                     = $roleData['name'];
            $result['description']              = $roleData['description'];
            $result['privilegios']              = $roleData['privilegios'];
            $result['menus']                    = $roleData['menus'];
            $result['path']                     = $roleData['path'];
        }
        
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
        $result['label']                    = $menuItemData['label'];
        $result['description']              = $menuItemData['description'];
        $result['icon']                     = $menuItemData['icon'];
        $result['path']                     = $menuItemData['path'];
        $result['order']                    = $menuItemData['objOrder'];

        return $result;
    }

    public function resetPassword($p_model, $p_id, $p_password){
        
        $result                             = false;

        $dataSourceOptions                  = array();
        $dataSourceOptions['model']         = $p_model;

        $dataSource                         = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));

        $userData                           = array();
        $userData['password']               = $p_password;
        $userData['password_reset']         = "0";

        $result                             = $dataSource->editData($p_id, $userData);
        
        return $result;
    }
}