<?php

class d_tasks_addedit extends global_tasks {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
  //=========================================
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
    if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { if($_POST){ output_error($this->fail_page,1); }else{ output_error($this->fail_page); } return; } //==============
    // SET ADD OR EDIT
    if(!empty($this->content_data)) { $modal_type = "edit"; }
    else { $modal_type = "add"; }
		?>
		<form class="form_redirect" enctype="multipart/form-data" method="POST" link="<?=URL_PATH?>?page=<?=encode("a_tasks")?>&action=<?=encode("p_".$modal_type."_task")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h2 class="modal-title" id="myModalLabel">
					<i class="fa fa-<?=$this->widget_data['font_icon']?> f-c-themed"></i> <?=$this->widget_data['title']?>: <? if($modal_type == "edit"){ echo "Edit"; } else { echo "Add"; } ?>
				</h2>
			</div>
			<div class="modal-body">
				<ul class="clearfix ul-unstyled ul-inline m-t-n40 p-b-10">
					<li class="right p-l-10">
						<div class="bg-white p-5 p-l-10 p-r-10">
							<div class="checkbox checkbox-danger checkbox-inline">
								<input type="hidden" name="<?=encode("inputse")?>[<?=encode("privacy")?>]" value="<?=encode("1")?>">
								<input id="<?$c=uniqid()?><?=$c?>" name="<?=encode("inputse")?>[<?=encode("privacy")?>]" value="<?=encode("2")?>" type="checkbox" <? if(e_a($this->content_data,'privacy') == '2') { echo "checked"; }?>>
								<label for="<?=$c?>" class="f-s-12 uppercase" title="Hide this item from everyone but yourself and anyone linked to it.">Private</label>
							</div>
						</div>
					</li>
					<li class="right p-l-10">
						<div class="bg-white p-5 p-l-10 p-r-10">
							<div class="checkbox checkbox-danger checkbox-inline">
								<input type="hidden" name="<?=encode("inputse")?>[<?=encode("priority")?>]" value="<?=encode("1")?>">
								<input id="<?$c=uniqid()?><?=$c?>" name="<?=encode("inputse")?>[<?=encode("priority")?>]" value="<?=encode("2")?>" type="checkbox" <? if(e_a($this->content_data,'priority') == '2') { echo "checked"; }?>>
								<label for="<?=$c?>" class="f-s-12 uppercase" title="Mark this item as High Priority.">High Priority</label>
							</div>
						</div>
					</li>
				</ul>
				<div class="row m-b-xs">
					<div class="col-md-3">
						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-5 p-t-10 f-s-13 uppercase">Status:</label>
							<div class="col-md-7"><input class="form-control" type="text" name="<?=encode("inputs")?>[<?=encode("customstatus")?>]" value="<?=e_a($this->content_data,'customstatus')?>"></div>
						</div>
						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-5 p-t-10 f-s-13 uppercase">Status Color:</label>
							<div class="col-md-7"><input class="form-control colorpicker" type="text" name="<?=encode("inputs")?>[<?=encode("customstatuscolor")?>]" value="<? if(!empty($this->content_data['customstatuscolor'])){ echo $this->content_data['customstatuscolor']; } else { echo "#00d4bd"; } ?>"></div>
						</div>
					</div>
					<div class="col-md-9">
						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-2 p-t-10 f-s-13 uppercase">Title:</label>
							<div class="col-md-10"><input class="form-control" type="text" name="<?=encode("inputs")?>[<?=encode("title")?>]" value="<?=e_a($this->content_data,'title')?>"></div>
						</div>
						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-2 p-t-10 f-s-13 uppercase">Due Date:</label>
							<div class="col-md-10"><input class="form-control atepicker" type="text" name="<?=encode("inputs")?>[<?=encode("due_date")?>]" value="<?=e_a($this->content_data,'due_date')?>"></div>
						</div>
						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-2 p-t-10 f-s-13 uppercase">Description:</label>
							<div class="col-md-10"><div class="b-s-1 b-solid b-c-default"><textarea class="form-control tinymce" name="<?=encode("inputs")?>[<?=encode("message")?>]"><?=e_a($this->content_data,'message')?></textarea></div></div>
						</div>

						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-2 p-t-10 f-s-13 uppercase">Link To:</label>
							<div class="col-md-10"><? $this->_set_linkable_to(); ?></div>
						</div>
						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<? $this->_set_additional_options(); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<? if($modal_type == "edit") { ?>
					<input type="hidden" name="<?=encode("wheres")?>[<?=encode("content_id")?>]" value="<?=encode($this->content_data['content_id'])?>">
					<? if($this->member_data['group_level'] > '2' || $this->content_data['member_id'] == $this->member_data['member_id']) { ?>
						<a class="cnfm_me btn btn-danger pull-left" link="<?=URL_PATH?>?page=<?=encode("a_tasks")?>&action=<?=encode("p_delete_task")?>" istype="" isform="" message="Are you sure you want to delete this content?" data-item_id="<?=encode($this->content_data['content_id'])?>">Delete</a>
					<? } ?>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-themed"><? if($this->member_data['group_level'] > '2' && $this->content_data['approval'] == "1") { echo"Approve"; }else{ echo "Update"; } ?></button>
				<? } else { ?>
          <input type="hidden" name="<?=encode("inputse")?>[<?=encode("level")?>]" value="<?=encode("1")?>">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-themed">Submit <? if($this->member_data['group_level'] < '3' && $this->widget_data['approval'] == '2') { echo"Request"; } ?></button>
				<? } ?>
			</div>
		</form>
    <script>
      $(".atepicker").dateRangePicker({
        separator : ' ~ ',
        format: 'YYYY.MM.DD HH:mm:ss',
        autoClose: false,
        container: $(".atepicker").closest("div"),
        singleDate : true,
      	singleMonth: true,
        time: {
          enabled: true
        }
      });
    </script>
		<?
    if($_POST) { echo json_encode(array('status'=>'success','message'=>'','data'=>ob_get_clean(),'js'=>'')); }
	}
  //=========================================

}
?>
