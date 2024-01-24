<?php
class Router
{
    private $page = "/";
    private $component = "";
    private $title="ລະບົບໂຮງຫວຍ";
    function __construct($page, $component, $title)
    {
        $this->page = $page;
        $this->component = $component;
        $this->title = $title;

        if (isset($_GET['page'])) {
            $pram = $_GET['page'];
            if ($this->page == $pram) {
                $_SESSION['title'] = $this->title;
                include_once($this->component);
            }
        }
    }
}
