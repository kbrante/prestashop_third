<?php


class AdminBlogController extends ModuleAdminController

{

    public function __construct()
 {

    $this->table='blog';
    $this->className='BlogPost';
    $this->bootstrap=True;

     $this->fields_list = array(

       'blog_name' => array(
           'title' => $this->l('blog_name'),
       ),
       'blog_description' => array(
           'title' => $this->l('blog_description'),
       ),
       'date_blog' => array(
           'title' => $this->l('date_blog'),
       ),
   );

   parent::__construct();


   $this->fields_form = array(
  'legend' => array(
    'title' => $this->l('Edit Blog'),
  ),
  'input' => array(
    array(
      'type' => 'text',
      'name' => 'blog_name',
      'label'=> 'Nom'
     ),
    array(
      'type' => 'textarea',
      'name' => 'blog_description',
      'label'=> 'Description'
     ),
    array(
      'type' => 'date',
      'name' => 'date_blog',
      'label'=> 'Date'
     ),
  ),
  'submit' => array(
    'title' => $this->l('Save'),
    'class' => 'btn btn-default pull-right'
  )
);
 }

}
