# Form
---
See [updates](updates.md) and [todo](todo.md)...

```html
<!-- register.html -->
<form method="POST" name="registration">
    <h2>{Registration}</h2>
    <div>
        <span>{Username}</span>
        <input type="text" size="26" name="username" value="{registration_value_username}">
        <em>{registration_mask_username}</em>
    </div>
    <div>
        <span>{Password}</span>
        <input type="password" size="26" name="password" value="{registration_value_password}">
        <em>{registration_mask_password}</em>
    </div>
    <div>
        <span>{Email}</span>
        <input type="text" size="26" name="email" value="{registration_value_email}">
        <em>{registration_mask_email}</em>
    </div>
    <div class="submit">
        <input type="submit" name="registration" value="{Register}">
    </div>
    {registration_form_message}
</form>
```
```php
<?php
namespace App\Pages;
use Letid\Request\Form;
class user extends \App\Page
{
    public function __construct()
    {
        $this->registration_table ='users';
    }
    public function registration()
    {
         // POST:default, GET, REQUEST
        $this->registration_method = 'POST';
        // Form default message
        $this->registration_message = 'this is message';
        // Form setting
        $this->registration_setting = array(
            'username'=>array(
                'value'=>'defaultUsernameFromValue',
                'require'=>array(
                    'mask'=>'Required',
                    'status'=>'Username'
                ),
                'custom'=>array(
                    'duplicate_check'=>array(
                        'mask'=>'Exists',
                        'status'=>'Username is not available.'
                    )
                )
            ),
            'password'=>array(
                'value'=>'test',
                'require'=>array(
                    'mask'=>'Required',
                    'status'=>'Password'
                ),
                'custom'=>array(
                    'let_modify_password'=>array(
                        'modify'=>true
                    )
                )
            ),
            'email'=>array(
                'value'=>'test@test.',
                'require'=>array(
                    'mask'=>'Required',
                    'status'=>'Email'
                ),
                'validate'=>array(
                    'task'=>'email',
                    'mask'=>'Invalid',
                    'status'=>'a valid Email'
                ),
                'custom'=>array(
                    'arguments_as_array'=>array(
                        'task'=>array('first','second','third'),
                        'mask'=>'..',
                        'status'=>'...'
                    ),
                    'arguments_as_string'=>array(
                        'task'=>'first',
                        'mask'=>'..',
                        'status'=>'...'
                    ),
                    'arguments_empty'=>array(
                        'mask'=>'Exists',
                        'status'=>'Email is already exists.'
                    )
                )
            ),
            'created'=>array(
                'value'=>date('Y-m-d G:i:s')
            ),
            'modified'=>array(
                'value'=>date('Y-m-d G:i:s')
            )
        );
        // Form action
        Form::name('registration')->setting($this)->response();
        Form::name('registration')->setting($this)->login();
        Form::name('registration')->setting($this)->insert();
        Form::name('registration')->setting($this)->update();
        Form::name('registration')->setting($this)->delete();
        // Require template
        return array(
            'layout'=>array(
                'page.id'=>'register',
                'page.class'=>'register',
                'Title'=>'Register',
                'Description'=>'Registration',
                'Keywords'=>'PHP framework',
                'page.content'=>$this->template(
                    array(
                        'register'=>array(
                        )
                    )
                )
            )
        );
    }
    public function arguments_as_array($first, $second, $third, $value,$name)
    {
        return 'true or false';
    }
    public function arguments_as_string($first, $value, $name)
    {
        return 'true or false';
    }
    public function arguments_empty($value,$name)
    {
        return 'true or false';
    }
    public function let_modify_password($value,$name)
    {
        return sha1($value);
    }
    public function duplicate_check($value, $name)
    {
        return 'yes have we have the same username';
    }
}
```

