<?php
namespace Binssoft\Permissionmanager;
class PermissionManager {
    
    function __construct()
    {

    }
    /**
     * Name : assignUserRole
     * Perpous : assign user role
     *  */ 
    public static function assignUserRole($userId, $roleId)
    {
        $userRole = null;
        $baseRoler = new BaseRoler();
        $userRole = $baseRoler->getModelObject('UserRoles');

        $userRole->user_id = $userId;
        $userRole->role_id = $roleId;
        $userRole->save();
        return true;
    }
    /**
     * Name : getUserRole
     * Perpous : get role of a user
     *  */ 
    public static function getUserRole($userId) {
        $userRole = null;
        $baseRoler = new BaseRoler();
        $userRole = $baseRoler->getModelObject('UserRoles');
        $userRoleData = $userRole->where('user_id', '=', $userId)->with(['role'])->first();
        if ($userRoleData) {
            return ($userRoleData->role)?$userRoleData->role:null;
        } else {
            return false;
        }
    }
    /**
     * Name : navigations
     * Perpous : get all navigations from router
     *  */ 
    public static function navigations($config=[])
    {
        
        $routeCollection = \Route::getRoutes();
        $routeList = [];
        foreach($routeCollection as $route) {
            if ( is_array($route->action) && array_key_exists('namespace', $route->action) ) {

                $namespace = $route->action['namespace'];
                $nameSpaceConfig = array_filter($config['namespace'], function($item) use ($namespace) {
                    return strstr($namespace,$item) !== false;
                }) ;
                
                if ((is_array($route->methods) && !in_array("POST", $route->methods) )
                && (is_array($route->action['middleware']) && !in_array('api',$route->action['middleware']) )
                && ( array_key_exists('namespace', $config) && count($nameSpaceConfig) > 0 ) 
                ){
                    $pathArr = explode('/',$route->uri);
                    $pathArr = array_filter($pathArr, function($item){
                        return strstr($item,"{") == "";
                    });
                    $pathArr = array_map(function($item){
                        return ucfirst(str_replace('-', ' ', $item));
                    }, $pathArr);
                    $path = implode(" >> ",$pathArr);
                    $routeList[] = [
                        // 'namespace' => $namespace,
                        "name"=> $route->getName(),
                        "path" => [
                           "display"=> $path,
                           "segment"=> $pathArr
                        ],
                    ] ;
                }
            }
        }
        return $routeList;
    }

    /**
     * Name : setRolePermission
     * Perpous : set role permission
     *  */ 
    public static function setRolePermission($roleId, $navName, $status = true) {
        $baseRoler = new BaseRoler();
        $navigations = $baseRoler->getModelObject('Navigations');
        $pageDetails = $navigations->where("nav_name", $navName)->first();
        $pageId = null;
        if ($pageDetails) {
            $pageId = $pageDetails->id;
        } else {
            $navigations->nav_name = $navName;
            $navigations->save();
            $pageId = $navigations->id;
        }
        if ($pageId) {
            $rolePermission = $baseRoler->getModelObject('RolePermissions');
            $permissionData = $rolePermission->where('role_id', $roleId)->where("nav_id", $pageId)->first();
            if (!$permissionData) {
                $permissionData = $rolePermission;
            }
            if ($status) {
                $permissionData->role_id = $roleId;
                $permissionData->nav_id = $pageId;
                $permissionData->save();
                echo "saved";
            } else {
                $rolePermission->where('role_id', $roleId)->where("nav_id", $pageId)->delete();
                echo "delete";
            }
        }
    }

        /**
     * Name : access
     * Perpous : check role access
     *  */
    public static function access($roleId, $pageName=null) {
        if (!$pageName) {
            $currentRoute = \Route::getCurrentRoute();
            $pageName = $currentRoute->getName();
        }
        $baseRoler = new BaseRoler();
        $navigations = $baseRoler->getModelObject('Navigations');
        $pageDetails = $navigations->where("nav_name", $pageName)->first();
        
        if($pageDetails) {
            $rolePermission = $baseRoler->getModelObject('RolePermissions');
            $permissionData = $rolePermission->where('role_id', $roleId)->where("nav_id", $pageDetails->id)->first();
        } else {
            return true;
        }
        if ($permissionData) {
            return true;
        } else {
            return false;
        }


    }
}

class BaseRoler {
    public function getModelObject($modelName) {
        $model = null;
        $class = '';
        if (class_exists("\App\\{$modelName}")) {
            $class = "\App\\{$modelName}";
        } else if (class_exists("\App\models\\{$modelName}")) {
            $class = "\App\models\\{$modelName}";
        } else {
            return false;
        }
        $model = new $class();
        return $model;
    }
}


?>

