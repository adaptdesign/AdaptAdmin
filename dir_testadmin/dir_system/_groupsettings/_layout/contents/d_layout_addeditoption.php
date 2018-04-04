<?php

class d_layout_addeditoption extends global_layout {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
  public function p_edit() {
    if(!empty($this->content_data_id)) { $this->set_content_data("",$this->content_data_id); }
    if(empty($this->content_data)) { $this->fail_page[] = "Unable to load content data"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { if($_POST){ output_error($this->fail_page,1); }else{ output_error($this->fail_page); } return; } //==============
    $this->p_addedit();
  }
	//=========================================
  public function p_addedit() { if($_POST) { ob_start(); }
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
		if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { if($_POST){ output_error($this->fail_page,1); }else{ output_error($this->fail_page); } return; } //==============
    // SET ADD OR EDIT
    if(!empty($this->content_data)) { $modal_type = "edit"; }
    else { $modal_type = "add"; }

    $parent_id = preg_replace('/[^a-z A-Z0-9-_\,\.\/\:\=\@\|]/','', decode($_GET['parent']));
    $type = preg_replace('/[^a-z A-Z0-9-_\,\.\/\:\=\@\|]/','', decode($_GET['type']));
    ?>
    <form class="form_redirect" enctype="multipart/form-data" method="POST" link="<?=URL_PATH?>?page=<?=encode("a_layout")?>&action=<?=encode("p_".$modal_type."_option")?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title" id="myModalLabel">
          <i class="fa fa-plus f-c-themed"></i> Additional Inputs: <? if($modal_type == "edit"){ ?>Edit<? } else { ?>Add New<? } ?>
        </h2>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
              <label class="col-md-4 p-t-10 f-s-13 uppercase">Title:</label>
              <div class="col-md-8"><input class="form-control" type="text" name="<?=encode("inputs")?>[<?=encode("title")?>]" value="<?=$this->content_data['title']?>"></div>
            </div>
            <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
              <label class="col-md-4 p-t-10 f-s-13 uppercase" title="">Input Type: <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
              <div class="col-md-8">
                <select id="option_type" class="form-control select2" name="<?=encode("inputs")?>[<?=encode("option_type")?>]">
                  <option value="input" <? if($this->content_data['option_type'] == "input") { echo "selected"; } ?>>Text Input</option>
                  <option value="checkbox" <? if($this->content_data['option_type'] == "checkbox") { echo "selected"; } ?>>Checkbox</option>
                  <option value="select" <? if($this->content_data['option_type'] == "select") { echo "selected"; } ?>>Select Dropdown</option>
                  <option value="label" <? if($this->content_data['option_type'] == "label") { echo "selected"; } ?>>Section Label</option>
                </select>
              </div>
            </div>

            <div id="option_content">
              <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1 m-t-15">
                <label class="col-md-4 p-t-10 f-s-13 uppercase">Dropdown Options:</label>
                <div class="col-md-8">
                  <div class="option_section">
                    <?
                    if(!empty($this->content_data['option_options'])) {
                      $option_parts = explode("|",$this->content_data['option_options']);
                      if(!empty($option_parts)) { foreach($option_parts as $row) {
                        ?>
                        <div class="input-group">
                          <input type="text" class="form-control" name="option_options[]" value="<?=$row?>">
                          <span class="input-group-btn">
                            <button class="btn btn-default remove_option" type="button"><i class="fa fa-trash" title="Remove"></i></button>
                          </span>
                        </div>
                        <?
                      } }
                    } else {
                      ?>
                      <div class="input-group">
                        <input type="text" class="form-control" name="option_options[]">
                        <span class="input-group-btn">
                          <button class="btn btn-default remove_option" type="button"><i class="fa fa-trash" title="Remove"></i></button>
                        </span>
                      </div>
                      <?
                    }
                    ?>
                  </div>
                  <a id="add_option" class="pull-right btn btn-block btn-sm btn-themed m-t-5"><i class="fa fa-plus"></i> Add</a>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <? if($modal_type == "edit") { ?>
          <input type="hidden" name="<?=encode("wheres")?>[<?=encode("layout_id")?>]" value="<?=encode($this->content_data['layout_id'])?>">
          <? if($this->member_data['group_level'] > '3' || $this->content_data['member_id'] == $this->member_data['member_id']) { ?>
            <a class="cnfm_me btn btn-sm btn-danger pull-left" link="<?=URL_PATH?>?page=<?=encode("a_layout")?>&action=<?=encode("a_delete_option")?>" istype="" isform="" message="Are you sure you want to delete this input?" data-item_id="<?=encode($this->content_data['layout_id'])?>">Delete</a>
          <? } ?>
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-sm btn-themed">Update</button>
        <? } else { ?>
          <input type="hidden" name="<?=encode("inputse")?>[<?=encode("plugin_id")?>]" value="<?=encode($parent_id)?>">
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-sm btn-themed">Submit</button>
        <? } ?>
      </div>
    </form>
    <script type="text/javascript">
      if($("#option_type").val() == "select"){
        $('#option_content').show();
      } else {
        $('#option_content').hide();
      }
    </script>
    <?
    if($_POST) { echo json_encode(array('status'=>'success','message'=>'','data'=>ob_get_clean(),'js'=>'')); }
  }

}
?>
