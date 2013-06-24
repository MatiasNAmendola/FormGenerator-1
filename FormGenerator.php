<?php

/**
 * FormGenerator
 * 
 * This version of a FormGenerator class is written by Petar Fedorovsky 22.06.2013
 * 
 * Please retain this credit when displaying this code online.
 * 
 * This is array driven form generation class
 * 
 * @version 1.0 22.06.2013
 * @author Petar Fedorovsky
 */
class FormGenerator {

    private $start = '';
    private $storage = '';
    private $label;
    private $hidden = array();
    private $output;

    /**
     * Constructor
     *
     * Accepts either:
     * - An associative array of option
     * - nothing
     * 
     * Creates a start <form> tag with attributes that are key / value pairs 
     * 
     * If nothing is passed, creates a default form tag: 
     * 
     * <form action="$_SERVER['PHP_SELF']" method="post" id="form" >
     * 
     * Here is an example:
     * 
     *  <code>
     * $f = new FormGenerator(array('action' => $_SERVER['PHP_SELF'],
     * 'method' => 'post',
     * 'id' => 'form1',
     * ));
     *  </code>
     * @param  array $parameters associative array
     * @return void     
     */
    public function __construct($parameters = false) {

        if ($parameters != false && is_array($parameters)) {
            foreach ($parameters as $key => $value) {
                $option .= $key . '="' . $value . '" ';
            }
            $this->start = '<form ' . $option . '>';
        } else {
            $this->start = '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" id="form" >';
        }
    }

    /**
     * input
     * 
     * Creates a <input> tag with attributes that are key / value pairs 
     * 
     * Types of input:
     * - all
     *
     * Attributes:
     * - all
     * 
     * Special:
     * - radio
     * - checkbox
     * 
     * -if needed a checkbox with multiple checked items in a value of name attribute add "[]" - example: 'name'=>'dogs[]'
     *  
     * The sintax applies to both radio and checkboxes
     * 
     * Example 1:
     * 
     * Radio button with label 'Radio' to the left of the button and value="male" and text to the right of the radio button 'Male'
     *  
     * <code>
      $f->input(array('type' => 'radio',
      'name' => 'radiotest',
      'label' => 'Radio',
      'value' => 'Male',
      ));
     * </code>
     * Example 2:
     * 
     * Radio button with no label to the left of the button and value="2" and text to the right of the radio button 'Female'
     *  
     * <code>
      $f->input(array('type' => 'radio',
      'name' => 'radiotest',
      'value' => array(1, 'Female'),
      ));
     *  </code>
     * 'value' attribute:
     * - can be a string or an array
     * - First parameter of an array is the value, and the second is text that will be displayed to the right of the button
     * 
     * @param  array $parameters associative array
     * @return void
     * @link http://www.w3schools.com/tags/tag_input.asp
     */
    public function input($parameters = array()) {
        if (in_array('hidden', $parameters)) {
            foreach ($parameters as $key => $value) {
                $option .= $key . '="' . $value . '" ';
            }
            $this->hidden[] = ($this->label) ? $this->label . '<input ' . $option . '>' : '<input ' . $option . '>';
        } elseif (in_array('radio', $parameters) || in_array('checkbox', $parameters)) {
            $parameters = $this->setlabel($parameters);
            foreach ($parameters as $key => $value) {

                if ($key == 'value') {
                    if (is_array($value)) {
                        $valueField = $value[1];
                        $option .= $key . '="' . $value[0] . '" ';
                    } else {
                        $valueField = $value;
                        $option .= $key . '="' . strtolower($value) . '" ';
                    }
                } else {
                    $option .= $key . '="' . strtolower($value) . '" ';
                }
            }
            $this->storage .= ($this->label) ? $this->label . '<input ' . $option . '>' . $valueField . '<br />' . "\n" : '<input ' . $option . '>' . $valueField . '<br />' . "\n";
            $this->label = false;
        } else {
            $parameters = $this->setlabel($parameters);

            if (is_array($parameters)) {
                foreach ($parameters as $key => $value) {
                    $option .= $key . '="' . $value . '" ';
                }
            }
            $this->storage .= ($this->label) ? $this->label . '<input ' . $option . '>' . '<br />' . "\n" : '<input ' . $option . '>' . '<br />' . "\n";
            $this->label = false;
        }
    }

    /**
     * button
     * 
     * Creates a <button> tag with attributes that are key / value pairs 
     * 
     * Types of buttons:
     * - all
     *
     * Attributes:
     * - all
     * - special: 'text' - a text that will apear in the button
     * 
     * Example:
     * 
     * <code>
      $f->button(array('type' => 'button',
      'name' => 'buton',
      'value' => 'button_value',
      'text' => 'Custom Button',
      ));
     * </code>

     * @param  array $parameters associative array
     * @return void
     * @link http://www.w3schools.com/tags/tag_button.asp
     */
    function button($parameters = array()) {

        $parameters = $this->setlabel($parameters);

        foreach ($parameters as $key => $value) {
            if ($key == 'text') {
                $buttonLabel = $value;
                continue;
            }
            $option .= $key . '="' . $value . '" ';
        }

        $this->storage .= ($this->label) ? $this->label . '<button ' . $option . '>' . $buttonLabel . '</button>' . '<br />' . "\n" : '<button ' . $option . '>' . $buttonLabel . '</button>' . '<br />' . "\n";
        $this->label = false;
    }

    /**
     * textarea
     * 
     * Creates a <textarea> tag with attributes that are key / value pairs 
     *
     * Attributes:
     * - all
     * - special: 'text' - a text that will apear in the textatea
     * 
     * Example:
     * 
     * <code>
      $f->textarea(array('name' => 'textarea',
      'rows' => 4,
      'cols' => 20,
      'maxlength' => '94',
      'text' => 'Text inside the field',
      'label' => 'Text field',
      ));
     * </code>
     * @param  array $parameters associative array
     * @return void
     * @link http://www.w3schools.com/tags/tag_textarea.asp
     */
    function textarea($parameters = array()) {
        $parameters = $this->setlabel($parameters);

        foreach ($parameters as $key => $value) {
            if ($key == 'text') {
                $text = $value;
                continue;
            }
            $option .= $key . '="' . $value . '" ';
        }

        $this->storage .= ($this->label) ? $this->label . '<textarea ' . $option . '>' . $text . '</textarea>' . '<br />' . "\n" : '<textarea ' . $option . '>' . $text . '</textarea>' . '<br />' . "\n";
        $this->label = false;
    }

    /**
     * fieldsetStart
     * 
     * Creates a <fieldset> tag with attributes that are key / value pairs 
     *
     * Attributes:
     * - all
     * - special: 'legend' - a text that will apear between the <legend></legend> tags
     * 
     * Example:
     * 
     * <code>
      $f->fieldsetStart(array('legend' => 'Some legend'));
     * </code>
     * @param  array $parameters associative array
     * @return void
     * @link http://www.w3schools.com/tags/tag_fieldset.asp
     */
    function fieldsetStart($parameters = array()) {

        foreach ($parameters as $key => $value) {
            if ($key == 'legend') {
                $legend = '<legend>' . $value . '</legend>' . "\n";
                continue;
            }

            $option .= $key . '="' . $value . '" ';
        }

        $this->storage .= '<fieldset ' . $option . '>' . "\n";
        $this->storage .= ($legend) ? $legend : false;
    }

    /**
     * fieldsetEnd
     * 
     * Creates a end tag for </fieldset> element
     *
     * Example:
     * 
     * <code>
      $f->fieldsetEnd();
     * </code>
     */
    function fieldsetEnd() {
        $this->storage .= '</fieldset>' . '<br />' . "\n";
    }

    /**
     * select
     * 
     * Creates a <select> tag with attributes that are key / value pairs 
     *
     * Attributes:
     * - all
     * 
     * Special: 
     * - 'options' - associative array where key is value of option and value is text of option
     * 
     * Options special:
     * -optStart - creates a <optgroup> tag, if multiple optgroup's needed than add a number after first optStart(1 than 2 than 3 and so on...), as in example 
     * -optStart value is an associative array with attributes that are key / value pairs
     * -optEnd - creates a </optgroup> end tag. The name must corespond to optStart tag
     * 
     * 
     * Example:
     * 
     * <code>
      $f->select(array(
      'label' => 'Select box3',
      'options' => array('audi' => 'Audi',
      'optStart' => array('label' => 'Svabi', 'disabled'=>'disabled'),
      'peugot' => 'Peugot',
      'saab' => array('text' => 'Saab', 'selected' => 'selected'),
      'optEnd' => '',
      'zastava' => 'Zastava',
      'optStart1' => array('label' => 'Taliani'),
      'fico' => 'Fico',
      'peglica' => 'Peglica',
      'optEnd1' => ''),
      'name' => 'seelct3',
      ));
     * </code>
     * @param  array $parameters associative array
     * @return void
     * @link http://www.w3schools.com/tags/tag_select.asp
     * @link http://www.w3schools.com/tags/tag_option.asp
     * @link http://www.w3schools.com/tags/tag_optgroup.asp
     */
    public function select($selectbox = array()) {

        $selectbox = $this->setlabel($selectbox);

        foreach ($selectbox as $key => $value) {
            if ($key == 'options' && is_array($value)) {
                foreach ($value as $k => $v) {
                    if ($k == ($optStart = 'optStart' . $numStart)) {
                        foreach ($v as $optgK => $optgV) {
                            $optg .=$optgK . '="' . $optgV . '" ';
                        }
                        $option .='<optgroup ' . $optg . ' >' . "\n";
                        $numStart++;
                        $optg = false;
                        continue;
                    }
                    if ($k == ($optStart = 'optEnd' . $numEnd)) {
                        $option .='</optgroup>' . "\n";
                        $numEnd++;
                        continue;
                    }
                    if ($k != 'optStart' && is_array($v)) {

                        foreach ($v as $opk => $opv) {
                            if ($opk == 'text') {
                                $option_text = $opv;
                                continue;
                            }
                            $option_attr .= $opk . '="' . $opv . '" ';
                        }
                        $option .= '<option value="' . $k . '" ' . $option_attr . '>' . $option_text . '</option>' . "\n";
                    } else {
                        $option .= '<option value="' . $k . '">' . $v . '</option>' . "\n";
                    }
                }
            } else {
                $select_options .= $key . '="' . $value . '" ';
            }
        }
        $select = '<select ' . $select_options . '>' . $option . '</select>' . "\n";
        $this->storage .= ($this->label) ? $this->label . $select . '<br />' . "\n" : $select . '<br />' . "\n";
        $this->label = false;
    }

    /**
     * render
     * 
     * closes <form> tag and returns a whole form in a string
     *
     * All fields are without any styleing and  after each method call there is a break ( <br /> ) tag and a new line ( \n ).
     * 
     * 
     * Example:
     * 
     * <code>
      $f->render();
     * </code>
     * 
     * @return string
     */
    public function render() {
        $this->output = $this->start . "\n";
        !empty($this->hidden) ? $this->output .= implode("\n", $this->hidden) . "\n" : false;
        //$this->output .= implode("<br />\n", $this->storage) . "\n";
        $this->output .=$this->storage . "\n";
        $this->output .= '</form>' . "\n";

        return $this->output;
    }

    /**
     * setLabel
     * 
     * private function
     * 
     * creates <label></label> tag stores it in the temporary variable
     *
     * All labels are displayed on the left side of the field.
     * 
     * Value can be a string or associative array where attributes are key / value pairs 
     * 
     * Attributes:
     * - all  
     * 
     * Example:
     * 
     * <code>
      $f->any method(array('label'=>array('value' => 'Email',
      'for' => 'email2',
      'form' => 'form1',
      )));
     * </code>
     * 
     * @return string
     * @link http://www.w3schools.com/tags/tag_label.asp
     */
    private function setlabel($parameters) {
        if (array_key_exists('label', $parameters)) {
            if (is_array($parameters['label'])) {
                foreach ($parameters['label'] as $key => $value) {
                    if ($key == 'value') {
                        continue;
                    }
                    $label_options .= $key . '="' . $value . '" ';
                }
                $this->label = '<label ' . $label_options . '>' . $parameters['label']['value'] . '</label>';
                unset($parameters['label']);
            } else {
                $this->label = '<label>' . $parameters['label'] . '</label>';
                unset($parameters['label']);
            }
        }
        return $parameters;
    }

}

?>