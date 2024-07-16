<?php

class UsePermission
{
    private $permission;
    private $key;
    function __construct($userID)
    {
        require_once ("connectDB.php");
        $connnect = new connectDB();
        $db = $connnect->getConnection();
        $sql = "SELECT * FROM tb_userpermission AS usp
        INNER JOIN tb_permission AS pm ON usp.permissionID = pm.permissionID
        WHERE usp.userID = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$userID]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->permission = json_decode($result[0]['permission'] ?? null, true);
    }

    function getValue($key)
    {
        $this->key = $key;
        foreach ($this->permission as $item) {
            if (isset($item[$key])) {
                return $item[$key];
            }
        }
        return [];
    }

    function setView($code, $values, $path, $title)
    {
        $isPermiss = in_array($code, $values);
        if ($isPermiss) {
            require_once ("./router/startRoute.php");
            new Router($this->key, $path, $title);
        } else {
            $this->toHomePage();
        }
    }

    function isPermission($code, $values)
    {
        return in_array($code, $values);
    }


    function isMenu($key)
    {
        $read = $this->getValue($key);
        if ($read) {
            return $this->isPermission(1, $read);
        } else {
            return false;
        }
    }
    function isAction($key, $actionCode)
    {
        $read = $this->getValue($key);
        if ($read) {
            return $this->isPermission($actionCode, $read) ? "" : "hidden";
        } else {
            return "hidden";
        }
    }



    function getPermission()
    {
        return $this->permission;
    }

    function toHomePage()
    {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            if ($page == $this->key) {
                require_once "./views/Permission404.php";
            }
        }
    }


}