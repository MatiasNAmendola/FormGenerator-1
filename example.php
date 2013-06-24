<?php
function pre($variable){echo '<pre>';print_r($variable);echo'</pre>';}
include 'FormGenerator.php';

$f = new FormGenerator(array('action' => $_SERVER['PHP_SELF'],
            'method' => 'post',
            'id' => 'form1',
        ));
$f->fieldsetStart(array('legend' => 'Some'));
$f->input(array('type' => 'text',
    'name' => 'user',
    'label' => 'User',
));

$f->input(array('type' => 'password',
    'name' => 'pass',
    'label' => 'Password',
));

$f->button(array('type' => 'button',
    'name' => 'buton',
    'value' => 'button_value',
    'text' => 'Custom Button',
));
$f->fieldsetEnd();

$f->input(array('type' => 'hidden',
    'name' => 'email',
));
$f->input(array('type' => 'hidden',
    'name' => 'email3',
    'value' => '',
));
$f->input(array('type' => 'text',
    'name' => 'email2',
    'label' => array('value' => 'Email',
        'for' => 'email2',
        'form' => 'form1',
    ),
    'id' => 'email2',
));
$f->input(array('type' => 'radio',
    'name' => 'radiotest',
    'label' => 'Radio',
    'value' => 'Male',
));
$f->input(array('type' => 'radio',
    'name' => 'radiotest',
    'label' => 'Female',
    'value' => '2',
));
$f->input(array('type' => 'radio',
    'name' => 'radiotest',
    'value' => array(1, 'Female'),
));
$f->input(array('type' => 'checkbox',
    'name' => 'check1',
    'label' => 'Check',
    'value' => 'Male',
));
$f->input(array('type' => 'checkbox',
    'name' => 'check[]',
    'label' => 'Female',
    'value' => '2',
));
$f->input(array('type' => 'checkbox',
    'name' => 'check[]',
    'value' => array(1, 'Female'),
));
$f->textarea(array('name' => 'textarea',
    'rows' => 4,
    'cols' => 20,
    'maxlength' => '94',
    'text' => 'Inside textarea field',
    'label' => 'Text area',
));
$f->select(array(
    'label' => 'Select',
    'options' => array('audi' => 'Audi',
        'peugot' => 'Peugot',
        'saab' => 'Saab',
        'zastava' => 'Zastava',
        'fico' => 'Fico',
        'peglica' => 'Peglica',),
    'name' => 'seelct1',
    'onchange' => 'javascript:submit();',
));
$f->select(array(
    'label' => 'Select box2',
    'options' => array('audi' => 'Audi',
        'peugot' => 'Peugot',
        'saab' => array('label' => 'Saab', 'selected' => 'selected'),
        'zastava' => 'Zastava',
        'fico' => 'Fico',
        'peglica' => 'Peglica',),
    'name' => 'seelct2',
));
$f->select(array(
    'label' => 'Select box3',
    'options' => array('audi' => 'Audi',
        'optStart' => array('label' => 'Group1', 'disabled'=>'disabled'),
        'peugot' => 'Peugot',
        'saab' => array('label' => 'Saab', 'selected' => 'selected'),
        'optEnd' => '',
        'zastava' => 'Zastava',
        'optStart1' => array('label' => 'Group2'),
        'fico' => 'Fico',
        'peglica' => 'Peglica',
        'optEnd1' => ''),
    'name' => 'seelct3',
));
$f->input(array('type' => 'reset',
    'value' => 'Reset',
));
$f->input(array('type' => 'submit',
    'value' => 'Submit',
));

$form = $f->render();

(isset($_POST) && !empty($_POST))?pre($_POST):false;
?>
<!DOCTYPE HTML>
<html>
<body>

<?php echo $form; ?>

</body>
</html> 