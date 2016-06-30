<?php
namespace Letid\Id;
class MenuId extends AssetId
{
    public function page($Id=array())
    {
        /*
        Application::menu(option)->navigation(value);
        */
        MenuPage::request($this->Id)->response($Id);
    }
    public function language($Id=array())
    {
        /*
        Application::menu(option)->language(value);
        */
        MenuLanguage::request($this->Id)->response($Id);
    }
}