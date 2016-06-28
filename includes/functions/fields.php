<?php
class fields{
    function __construct(){
   	}
	public function textBox($text){

        $text['label'] = isset($text['label']) ? $text['label'] : 'Enter Text Here: ';
		$text['value'] = isset($text['value']) ? $text['value'] : '';
		$text['name'] = isset($text['name']) ? $text['name'] : '';
		$text['class'] = isset($text['class']) ? 'input_text '.trim($text['class']) : 'input_text';
        $text['onlyField'] = isset($text['onlyField']) ? $text['onlyField'] : false;
		$text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
		if($text["onlyField"]==true){
             return '<input type="text" class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['name'].'" value="'.$text['value'].'" '.$text['extraAtt'].' />';
        }
        else{
            return '<div class="flclear clearfix"></div>
			<label for="'.$text['name'].'">'.$text['label'].'&nbsp;</label>
            <input type="text" class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['name'].'" value="'.$text['value'].'" '.$text['extraAtt'].' />';
       }
	}
	public function hidden($text) {
		$text['label'] = isset($text['label']) ? $text['label'] : 'Enter Text Here: ';
		$text['value'] = isset($text['value']) ? $text['value'] : '';
		$text['name'] = isset($text['name']) ? $text['name'] : '';
        $text['onlyField'] = isset($text['onlyField']) ? $text['onlyField'] : false;
        $text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';

            return '<div class="flclear clearfix"></div>
	            <input type="hidden" name="'.$text['name'].'" id="'.$text['name'].'" value="'.$text['value'].'" '.$text['extraAtt'].' />';

	}
	public function fileBox($text){

        $text['label'] = isset($text['label']) ? $text['label'] : 'Enter Text Here: ';
		$text['value'] = isset($text['value']) ? $text['value'] : '';
		$text['name'] = isset($text['name']) ? $text['name'] : '';
		$text['class'] = isset($text['class']) ? 'input_text '.trim($text['class']) : 'input_text';
        $text['onlyField'] = isset($text['onlyField']) ? $text['onlyField'] : false;
        $text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
		if($text["onlyField"]==true){
             return '<input type="file" class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['name'].'" value="'.$text['value'].'" '.$text['extraAtt'].' />';
        }
        else{
            return '<div class="flclear clearfix"></div>
			<label for="'.$text['name'].'">'.$text['label'].'&nbsp;</label>
            <span><input type="file" class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['name'].'" '.$text['extraAtt'].' />'.$text['value'].'</span>';
       }
	}
	public function fileBoxA($text){

        $text['label'] = isset($text['label']) ? $text['label'] : 'Enter Text Here: ';
		$text['value'] = isset($text['value']) ? $text['value'] : '';
		$text['name'] = isset($text['name']) ? $text['name'] : '';
		$text['class'] = isset($text['class']) ? 'input_text '.trim($text['class']) : 'input_text';
        $text['onlyField'] = isset($text['onlyField']) ? $text['onlyField'] : false;
        $text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
		if($text["onlyField"]==true){
             return '<input type="file" class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['name'].'" value="'.$text['value'].'" '.$text['extraAtt'].' />';
        }
        else{
            return '<div class="flclear clearfix"></div>
			<label for="'.$text['name'].'">'.$text['label'].'&nbsp;</label>
            <input type="file" class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['name'].'" value="'.$text['value'].'" '.$text['extraAtt'].' />';
       }
	}
	public function displayBox($text){

        $text['label'] = isset($text['label']) ? $text['label'] : 'Enter Text Here: ';
		$text['value'] = isset($text['value']) ? $text['value'] : '';
		$text['name'] = isset($text['name']) ? $text['name'] : '';
		$text['class'] = isset($text['class']) ? 'input_text '.trim($text['class']) : 'input_text';
        $text['onlyField'] = isset($text['onlyField']) ? $text['onlyField'] : false;
        $text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
			return '<div class="flclear clearfix"></div>
				<label>'.$text['label'].'&nbsp;</label>
				<span>'.$text['value'].'</span>';
	}
    # for use password and pass label,name,class,and value array
	public function password($text){
		$text['label'] = isset($text['label']) ? $text['label'] : 'Enter Text Here: ';
		$text['value'] = isset($text['value']) ? $text['value'] : '';
		$text['name'] = isset($text['name']) ? $text['name'] : '';
		$text['class'] = isset($text['class']) ? 'input_text '.trim($text['class']) : 'input_text';
        $text['onlyField'] = isset($text['onlyField']) ? $text['onlyField'] : false;
        $text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
		if($text["onlyField"]==true){
             return '<input type="password" class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['name'].'" value="'.$text['value'].'" '.$text['extraAtt'].' />';
        }
        else{
            return '<div class="flclear clearfix"></div>
				<label for="'.$text['name'].'">'.$text['label'].'&nbsp;</label>
            	<input type="password" class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['name'].'" value="'.$text['value'].'" '.$text['extraAtt'].' />';
       }

	}
	public function textAreaEditor($text){
        $text['label'] = isset($text['label']) ? $text['label'] : 'Enter Password Here: ';
		$text['value'] = isset($text['value']) ? $text['value'] : '';
		$text['name'] = isset($text['name']) ? $text['name'] : '';
		$text['class'] = isset($text['class']) ? 'textarea '.$text['class'] : 'textarea';
        $text['extraAtt'] = isset($text['extraAtt']) ? ' '.$text['extraAtt'] : '';
        $text['onlyField'] = isset($text['onlyField']) ? $text['onlyField'] : false;

			return '<div class="padtop10 flclear"></div>
			'.$text['label'].'&nbsp;<br />
            <textarea class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['name'].'" '.$text['extraAtt'].' >'.htmlentities($text['value']).'</textarea>
			 <script type="text/javascript">
				//<![CDATA[
				CKEDITOR.replace( \''.$text['name'].'\',
				{
					removePlugins: \'elementspath\' ,
					skin : \'kama\',
					width: \'665\',
					height: \'150\',

					attributes : { \'class\' : \'ck_req\' }
				});
			  //]]>
		  </script>';
	}

    # for use textarea and pass label,name,class,and value array
	public function textArea($text){
        $text['label'] = isset($text['label']) ? $text['label'] : 'Enter Password Here: ';
		$text['value'] = isset($text['value']) ? $text['value'] : '';
		$text['name'] = isset($text['name']) ? $text['name'] : '';
		$text['class'] = isset($text['class']) ?"textarea ".$text['class'] : 'textarea';
        $text['extraAtt'] = isset($text['extraAtt']) ? ' '.$text['extraAtt'] : '';
        $text['onlyField'] = isset($text['onlyField']) ? $text['onlyField'] : false;

        if($text["onlyField"]== true){
            return '<textarea class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['name'].'" '.$text['extraAtt'].' >'.$text['value'].'</textarea>';
        }
        else{
			return '<div class="flclear clearfix"></div>
				<label for="'.$text['name'].'">'.$text['label'].'&nbsp;</label>
    	        <textarea class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['name'].'" '.$text['extraAtt'].' >'.$text['value'].'</textarea>';
		}
	}
	public function checkBox($chk){
        $checkBoxes='';
        $chk['label'] = isset($chk['label']) ? $chk['label'] : ' ';
		$chk['value'] = isset($chk['value']) ? $chk['value'] : '';
		$chk['name'] = isset($chk['name']) ? $chk['name'] : array();
        $chk['class'] = isset($chk['class']) ? 'checkbox '.$chk['class'] : 'checkbox';
        $chk['extraAtt'] = isset($chk['extraAtt']) ? ' '.$chk['extraAtt'] : '';
        $chk['onlyField'] = isset($chk['onlyField']) ? $chk['onlyField'] : false;
		$chk['noDiv']=isset($chk['noDiv'])?true:'';

			foreach($chk['name'] as $k=>$v){
				$check=($k==$chk['value'])?'checked="checked"':'';
				$checkBoxes.='<input class="fl '.$chk['class'].'" name="'.$k.'" id="'.$k.'" type="checkbox" value="'.$k.'" '.$check.' '.$chk['extraAtt'].' />';
				if($chk['noDiv']==true){$checkBoxes.=ucwords($v);}else{
				$checkBoxes.='<div>'.ucwords($v).'</div>';
				}
			}

            if($chk['onlyField']==true){
               return $checkBoxes;
            }
            else {
	    	        return '<div class="flclear clearfix"></div>
					<label>'.$chk['label'].'&nbsp;</label>'.$checkBoxes;
            }
	}
	public function radio($radio){
        $radio['label'] = isset($radio['label']) ? $radio['label'] : 'Select Any One: ';
		$radio['values'] = isset($radio['values']) ? $radio['values'] :array();
        $radio['value'] = isset($radio['value']) ? $radio['value'] : '';
		$radio['name'] = isset($radio['name']) ? $radio['name'] : '';
        $radio['class'] = isset($radio['class']) ? 'radio '.$radio['class'] : 'radio';
        $radio['extraAtt'] = isset($radio['extraAtt']) ? ' '.$radio['extraAtt'] : '';
        $radio['onlyField'] = isset($radio['onlyField']) ? $radio['onlyField'] : false;
		$check='';
        $radios='';
		foreach($radio['values'] as $k=>$v){
			$check=($k==$radio['value'])?'checked="checked"':'';
			$radios.='<span class="radiobadge"><input class="'.$radio['class'].'" id="'.$radio['name'].'" name="'.$radio['name'].'" type="radio" value="'.$k.'" '.$check.' '.$radio['extraAtt'].' />&nbsp;'.ucwords($v)."&nbsp;&nbsp;
			</span>";
		}
        if($radio["onlyField"]== true){
            return $radios;
        }else {
            return '<div class="flclear clearfix"></div><label>'.$radio["label"].'</label>
            	'.$radios;
        }
	}

	function selectBox($field=array()){
		global $db;
        $fields='';
        $field['label'] = isset($field['label']) ? $field['label'] : array();
        $field['id'] = isset($field['id']) ? $field['id'] : $field['name'] ;
        $field['value'] = isset($field['value']) ? $field['value'] : '';
        $field['class'] = isset($field['class']) ? $field['class'] : '';
        $field['multiple'] = isset($field['multiple']) ? $field['multiple'] : false;
        $field['arr'] = isset($field['arr']) ? $field['arr'] : true;
        $field['defaultValue'] = isset($field['defaultValue']) ? $field['defaultValue'] : false;
        $field['allow_null'] = isset($field['allow_null']) ? $field['allow_null'] : false;
		$field['allow_null_value'] = isset($field['allow_null_value']) ? $field['allow_null_value'] : 0;
        $field['choices'] = isset($field['choices']) ? $field['choices'] : array();
        $field['optgroup'] = isset($field['optgroup']) ? $field['optgroup'] : false;
        $field['onlyField'] = isset($field['onlyField']) ? $field['onlyField'] : false;
        $field['intoDB'] = isset($field['intoDB']) ? $field['intoDB'] : array();

        $field['intoDB']["val"] = isset($field['intoDB']["val"]) ? $field['intoDB']["val"] : false;
		$field['intoDB']["groupBy"] = isset($field['intoDB']["groupBy"]) ? ' '.$field['intoDB']["groupBy"] : '';
		$field['intoDB']["orderBy"] = isset($field['intoDB']["orderBy"]) ? ' '.$field['intoDB']["orderBy"] : '';

        $field['extraAtt'] = isset($field['extraAtt']) ? ' '.$field['extraAtt'] : '';
		$field['isArray'] = isset($field['isArray']) ? $field['isArray'] : '';
		// no choices
		if(empty($field['choices'])) {
			//echo '<p>' . __("No choices to choose from",'acf') . '</p>';
			return false;
		}
		if($field['intoDB']["val"]==true) {
			$field['choices']=array();

            //var_dump($field['intoDB']);

			//$get1 = $db->select($field['intoDB']["table"],$field['intoDB']["fields"],$field['intoDB']["where"],$field['intoDB']["groupBy"],$field['intoDB']["orderBy"], 0);
			$get1 = $db->query("SELECT ".$field['intoDB']["fields"]." FROM ".$field['intoDB']["table"]." WHERE ".$field['intoDB']["where"]." ORDER BY ".$field['intoDB']["orderBy"]."");

            while($checkVal = mysql_fetch_array($get1)) {
				$field['choices'][$checkVal[$field['intoDB']["valField"]]] = $db->filtering($checkVal[$field['intoDB']["dispField"]],'input','string', '');
            }
		}
		else{

		}
		$multiple = '';
		if($field['multiple'] == 'true' || $field['multiple'] == true) {
			$multiple = ' multiple="multiple" size="5" ';
            if($field['arr']=='true'){
                $field['name'] .= '[]';
            }
		}

		if($field['isArray'] == 'true'){
			$fields.='<select name="' . $field['name'] . '" class="' . $field['class'] . '" ' . $multiple . ' '.$field['extraAtt'].'>';
		}
		else {
			$fields.='<select name="' . $field['name'] . '" id="' . $field['id'] . '" class="' . $field['class'] . '" ' . $multiple . ' '.$field['extraAtt'].'>';
		}

		// null
		if($field['allow_null'] == '1') {
			$fields.= '<option value="'.$field['allow_null_value'].'">Select</option>';
		}
		// loop through values and add them as options
		foreach($field['choices'] as $key => $value)
		{
			if($field['optgroup'])
			{
				// this select is grouped with optgroup
				if($key != '') $fields.= '<optgroup label="'.$key.'">';
				if($value)
				{
					foreach($value as $id => $label)
					{
						$selected = '';
						if(is_array($field['value']) && in_array($id, $field['value'])) {
							$selected = 'selected="selected"';
						}
						else
						{
							// 3. this is not a multiple select, just check normaly
							if($id == $field['value'])
							{
								$selected = 'selected="selected"';
							}
						}
						$fields.= '<option value="'.$id.'" '.$selected.'>'.$label.'</option>';
					}
				}
				if($key != '') $fields.= '</optgroup>';
			}
			else {
				$selected = '';
				if(is_array($field['value']) && in_array($key, $field['value']))
				{
					// 2. If the value is an array (multiple select), loop through values and check if it is selected
					$selected = 'selected="selected"';
				}
				else
				{
					// 3. this is not a multiple select, just check normaly
					if($key == $field['value'])
					{
						$selected = 'selected="selected"';
					}
				}
				$fields.= '<option value="'.$key.'" '.$selected.'>'.ucfirst(stripslashes($value)).'</option>';
			}
		}

		$fields.='</select>';

        if($field["onlyField"]==true){
            return $fields;
        }
        else{
            return '<div class="flclear clearfix"></div>
				<label>'.$field['label'].'&nbsp;</label><span>'.$fields.'</span>';
        }
	}
	public function button($btn){
		$btn['value'] = isset($btn['value']) ? $btn['value'] : '';
		$btn['name'] = isset($btn['name']) ? $btn['name'] : '';
		$btn['class'] = isset($btn['class']) ? $btn['class'] : '';
		$btn['type'] = isset($btn['type']) ? $btn['type'] : '';
		$btn['src'] = isset($btn['src']) ? $btn['src'] : '';
        $btn['extraAtt'] = isset($btn['extraAtt']) ? ' '.$btn['extraAtt'] : '';
        $btn['onlyField'] = isset($btn['onlyField']) ? $btn['onlyField'] : false;
		$b='<input type="'.$btn["type"].'" name="'.$btn["name"].'" class="'.$btn["class"].'" id="'.$btn["name"].'" value="'.$btn["value"].'" '.$btn['extraAtt'].' ';
		$b.=($btn["type"]=="image" && $btn["src"]!='')?'src="'.$btn["src"].'"':'';
		$b.=' />';

        if($btn['onlyField']== true){
             return '<span>'.$b.'</span>';
        }else{
             return '<div class="flclear clearfix"></div>
			 	<span>'.$b.'</span>';
        }
	}

	public function label($text){
        $text['label'] = isset($text['label']) ? $text['label'] : 'Enter Label Here: ';
		$text['value'] = isset($text['value']) ? $text['value'] : '';
		$text['name'] = isset($text['name']) ? $text['name'] : '';

		//$text['class'] = isset($text['class']) ? 'input_text '.$text['class'] : 'input_text';
        $text['class'] = isset($text['class']) ? 'input_text '.trim($text['class']) : 'input_text';
        //$text['extraAtt'] = isset($text['extraAtt']) ? ' '.$text['extraAtt'] : '';
        $text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
		return '<label class="'.$text['class'].'"><span>'.$text['label'].'</span></label>';
	}

	public function link($text){
        $text['href'] = isset($text['href']) ? $text['href'] : 'Enter Link Here: ';
		$text['value'] = isset($text['value']) ? $text['value'] : '';
		$text['name'] = isset($text['name']) ? $text['name'] : '';
		//$text['class'] = isset($text['class']) ? 'input_text '.$text['class'] : 'input_text';
        $text['class'] = isset($text['class']) ? ''.trim($text['class']) : '';
        //$text['extraAtt'] = isset($text['extraAtt']) ? ' '.$text['extraAtt'] : '';
        $text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
		return '<span><a href="'.$text['href'].'" class="'.$text['class'].'" value="'.$text['value'].'" '.$text['extraAtt'].'>'.$text['value'].'</a></span>';
	}

	public function img($text){
		$text['href'] = isset($text['href']) ? $text['href'] : '';
        $text['src'] = isset($text['src']) ? $text['src'] : 'Enter Image Path Here: ';
		$text['name'] = isset($text['name']) ? $text['name'] : '';
		$text['id'] = isset($text['id']) ? $text['id'] : '';
        $text['class'] = isset($text['class']) ? ''.trim($text['class']) : '';
		$text['height'] = isset($text['height']) ? ''.trim($text['height']) : 0;
		$text['width'] = isset($text['width']) ? ''.trim($text['width']) : 0;
        $text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
		return '<span><a href="'.$text['href'].'" class="thickbox"><img src="'.$text['src'].'" class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['id'].'" alt="" '.($text['width'] > 0 ? 'width="'.$text['width'].'"' : '').' '.($text['height'] > 0 ? 'height="'.$text['height'].'"' : '').' '.$text['extraAtt'].'></a></span>';
	}
}
?>