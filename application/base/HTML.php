<?php

class HTML
{
    public function getHeader()
    {
        //include(DIR_TEMPLATES . 'header.tpl.php');
    }

    public function getContent($content, $context)
    {
        $context = $context;
        include($content);
    }

    public function getFooter()
    {
        //include(DIR_TEMPLATES . 'footer.tpl.php');
    }


}