# Html
Generate HTML tags
```
/*
$this->html('tag:string','text:string', 'attr:array')
$this->HtmlEngine('tag:string','text:string', 'attr:array')
$this->HtmlEngine('div','Space problem');
$this->HtmlEngine('span','Ok',array('class'=>'testing'));
$this->HtmlEngine('hr');
array('<tag attr/>','<tag attr>text</tag>','text') ---> '/\w+/'
*/
```
```php
<?php
$this->html(
    array(
    	'ol'=>array(
    		'text'=>array(
    			array(
    				'li'=>array(
    					'text'=>array(
    						'strong'=>'isStrong', 'em'=>'isEm'
    					),
    					'attr'=>array(
    						'class'=>'firstClass'
    					)
    				)
    			),
    			array(
    				'li'=>array(
    					'text'=>'Second',
    					'attr'=>array(
    						'class'=>'secondClass'
    					)
    				)
    			)
    		),
    		'attr'=>array(
    			'class'=>'currentClass'
    		)
    	)
    )
);
```
```php
<?php
$this->html(
    array(
    	'div'=>array(
    		'text'=>array(
    			'a'=>array(
    				'text'=>array(
    					'span'=>'isSpan', 'em'=>'isEm'
    				),
    				'attr'=>array(
    					'href'=>'#'
    				)
    			)
    		),
    		'attr'=>array(
    			'class'=>'current'
    		)
    	)
    )
);

$this->html(
    array(
    	'div'=>array(
    		'text'=>'Message goes here',
    		'attr'=>array(
    			'class'=>'current'
    		)
    	)
    )
);
```
