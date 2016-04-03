# Template

Page method can return array or string...
```php
<?php
namespace App\Pages;
use App;
class home extends App\Page
{
    public function __construct()
    {
        /*
        constructor
        */
        $this->menu_page = $this->menu();
        $this->menu_user = $this->menu($Option);
    }
    public function home()
    {
        /*
        homepage
        */
        $content = $this->template(
            array(
                'home.li'=>array(
                    'home.li.one'=>'One of One',
                    'home.li.two'=>'One of Two',
                    'home.li.li'=>array(
                        'home.li.li.one'=>'Two of One',
                        'home.li.li.two'=>'Two of Two',
                    )
                )
            )
        );
        return array(
            'home'=>array(
                'page.id'=>'home',
                'page.class'=>'home',
                'Title'=>'Title is replaced',
                'Description'=>'Description is replaced',
                'Keywords'=>'PHP framework',
                'menu.page'=>$this->menu_page,
                'menu.user'=>$this->menu_user,
                'home.li'=>array(
                    'home.li.one'=>'One of One',
                    'home.li.two'=>'One of Two',
                    'home.li.li'=>array(
                        'home.li.li.one'=>'Two of One',
                        'home.li.li.two'=>'Two of Two',
                    )
                ),
                'home.loop'=>array(
                    array(
                        'loop.one'=>'Loop One of One',
                        'loop.two'=>'Loop One of Two',
                    ),
                    array(
                        'loop.one'=>'Loop Two of One',
                        'loop.two'=>'Loop Two of Two',
                    ),
                    array(
                        'loop.one'=>'Loop Two of One',
                        'loop.two'=>'last',
                    )
                )
            )
        );
    }
}
```
