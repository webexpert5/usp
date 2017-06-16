<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">


        <?php
        $form = new RM_PFBC_Form("form_import");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        $form->addElement(new Element_HTML('<div class="rmheader"><span style="text-transform:none">RegistrationMagic</span> Import Engine</div>'));
        if(isset($data->status) && $data->status==true )
        {
             
             $form->addElement(new Element_HTML("<div id='rm_import_progress' style='margin:20px'></div>"));
               $form->render();
            ?>
        <pre class='rm-pre-wrapper-for-script-tags'><script>
        
         jQuery( document ).ready(function() {
         jQuery( "#rm_import_progress" ).append("<b>Starting import!</b>" );
           
        var data = {
			'action': 'import_first'
		};
		jQuery.post(ajaxurl, data, function(response) {
                   if(response==0)
                                {
                                   jQuery( "#rm_import_progress" ).append("(Imported)<br><br>All forms successfully Imported.");
                                }
                                else{
                                     var pre= parseInt(response)-1;
                                     jQuery( "#rm_import_progress" ).append("</br></br>Importing RM Form--"+pre+"(Imported)</br></br>Importing RM Form--"+response+"");
                                    recursive_import(response);
                                }
                   
		});
                 });
            function recursive_import(form_id){
                var id=form_id;
                var data = {
                                    'action': 'import_first',
                                    'form_id':id
                            };
                            jQuery.post(ajaxurl, data, function(response) {
                                if(response==0)
                                {
                                   jQuery( "#rm_import_progress" ).append("(Imported)</br></br>All forms and their data is imported successfully.");
                                }
                                else{
                                   
                                     jQuery( "#rm_import_progress" ).append("(Imported)</br></br>Importing RM Form--"+response+"");
                                     
                                    recursive_import(response);
                                }
                            });
}
        </script></pre>
        
            <?php
        }
        else
        {
$form->addElement(new Element_HTML("<div id='upload'>"));
        $form->addElement(new Element_File(RM_UI_Strings::get('UPLOAD_XML'), "Forms", array("id" => "mailchimp_list","accept"=>".xml", "longDesc" => RM_UI_Strings::get('UPLOAD_XML_HELP'))));
       

        $form->addElement (new Element_HTMLL ('&#8592; &nbsp; Cancel', '?page=rm_form_manage', array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit", "onClick" => "jQuery.prevent_field_add(event,'This is a required field.')")));
         $form->addElement(new Element_HTML("</div>"));
        $form->addElement(new Element_HTML("<div id='import'></div>"));
        
        $form->render();
        }
        ?>
    </div>
</div>


   
<?php
