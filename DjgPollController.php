<?php
if (!defined('IN_CMS')) { exit(); }
class DjgPollController extends PluginController 
{
    public function __construct() {
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/djg_poll/views/sidebar'));
    }
    function index() {
        $this->add();
    }
	function add() {
		if(isset($_POST['djg_poll'])):
			$i=0;
			foreach ($_POST['djg_poll'] as $key => $value):($value=='')?$i++:''; endforeach; 
			foreach ($_POST['djg_poll']['a'] as $key => $value):($value=='')?$i++:''; endforeach;
				/* fill date */
			if ( (empty($_POST['djg_poll']['startvote'])) && (!empty($_POST['djg_poll']['endvote'])) ||
				(!empty($_POST['djg_poll']['startvote'])) && (empty($_POST['djg_poll']['endvote']))	):
				Flash::set('error', __('Fill in date the fields.')); Flash::init();
				$this->display('djg_poll/views/add', array('djg_poll' => $_POST['djg_poll']));
			elseif(
				/* diff date */
				( (!empty($_POST['djg_poll']['startvote'])) && (!empty($_POST['djg_poll']['endvote'])) ) &&
				($_POST['djg_poll']['startvote'] >= $_POST['djg_poll']['endvote'])
			):
				Flash::set('error', __('Start date is earlier than the end date.')); Flash::init();
				$this->display('djg_poll/views/add', array('djg_poll' => $_POST['djg_poll']));
			elseif ( (empty($_POST['djg_poll']['startvote'])) && (empty($_POST['djg_poll']['endvote'])) ):
				/* empty date fill, no lifetime */
				$i=$i-2;
			endif;
			if($i==0):
				// save new poll
				$q = $_POST['djg_poll'];
				$data = array(
					'pollq_question' => $_POST['djg_poll']['q']
				);
				Djgpoll::addNewPoll($_POST['djg_poll']);
				unset($q['a']);
				Flash::set('success', __('Poll added.'));
				redirect(get_url('plugin/djg_poll/polls'));
			else:
				// isset empty fields
				Flash::set('error', __('Complete the field (:i).',array(':i'=>$i))); Flash::init();
				$this->display('djg_poll/views/add', array('djg_poll' => $_POST['djg_poll']));
			endif;		
		else:
			// no post, empty form
			$this->display('djg_poll/views/add', array('djg_poll' => null));
		endif;
	}
	function edit($questionId, $pageId=1) {	
		if ( (isset($_POST['djg_poll']))&&(isset($_POST['djg_poll']['questionId'])) ):
		  if($_POST['djg_poll']['questionId'] != $questionId):
			Flash::set('error', __('Difrent ids'));
			redirect(get_url('plugin/djg_poll/polls/'.$pageId));
		elseif( 
		( (!empty($_POST['djg_poll']['startvote'])) && (!empty($_POST['djg_poll']['endvote'])) ) &&
				($_POST['djg_poll']['startvote'] >= $_POST['djg_poll']['endvote'])
		):
			Flash::set('error', __('Start date is earlier than the end date.'));
			 $this->display('djg_poll/views/edit', array('djg_poll' => $_POST['djg_poll'], 'questionId'=>$questionId, 'pageId'=>$pageId)); 
		  elseif (Djgpoll::editPoll($_POST['djg_poll'])):
			Flash::set('success', __('Changed have been saved.'));
			redirect(get_url('plugin/djg_poll/polls/'.$pageId));
		  else:
			Flash::set('error', __('Changed have not been saved. Try again.'));
			redirect(get_url('plugin/djg_poll/polls/'.$pageId));
		  endif;
       //($page_id!=0) ? redirect(get_url('plugin/djg_poll/edit/'.$pageId)) : redirect(get_url('plugin/djg_poll/edit'));
		else:
		// no post, get setting from DB
		$__CMS_CONN__ = Record::getConnection();
		$pollsq = $__CMS_CONN__->query('SELECT * FROM '.TABLE_PREFIX.'djg_pollsq WHERE pollq_id = '.$questionId.' LIMIT 1');
		$data = $pollsq->fetchAll();
		 $this->display('djg_poll/views/edit', array('djg_poll' => $data[0], 'questionId'=>$questionId, 'pageId'=>$pageId)); 
		endif;
	}
	function polls($page_number = 1) {
		$this->display('djg_poll/views/polls', array('page_number' => $page_number));
	}
  function statistics() {
    $__CMS_CONN__ = Record::getConnection();
    $pollsq = $__CMS_CONN__->query('SELECT pollq_id, pollq_question FROM '.TABLE_PREFIX.'djg_pollsq ORDER BY pollq_id DESC');
    $polls = $pollsq->fetchAll();
    //questions
    $qsaQ = $__CMS_CONN__->query('SELECT count(pollq_question) as t, SUM(pollq_totalvotes) as tVotes, SUM(pollq_totalvoters) as tVoters, SUM(pollq_active) as tActive, SUM(pollq_multiple) as tMultiple  FROM '.TABLE_PREFIX.'djg_pollsq WHERE 1');
    $qsa = $qsaQ->fetchAll();
    //answares
    $asaQ = $__CMS_CONN__->query('SELECT count(polla_answers) as tAnswares FROM '.TABLE_PREFIX.'djg_pollsa WHERE 1');
    $asa = $asaQ->fetchAll();
    //
    $error = 1;
    if(isset($_POST['djg_poll'])):
      if(Djgpoll::roznica_data($_POST['djg_poll']['end_date'],date('Y-m-d'),'days') < 0):
        Flash::set('error', __('End date is older than today.')); Flash::init();
        $error = 1;
      elseif(Djgpoll::roznica_data($_POST['djg_poll']['start_date'],$_POST['djg_poll']['end_date'],'days') < 0):
        Flash::set('error', __('Start date is older than End date.')); Flash::init();
        $error = 1;
      else:
        //Flash::set('success', __('ok')); Flash::init();
        $error = 0;
      endif;
    endif;
    /*
    if ($questionId != null):
      $s1q = $__CMS_CONN__->query('SELECT q.pollq_id, a.polla_aid, q.pollq_question, q.pollq_totalvotes, a.polla_answers,	a.polla_votes  FROM '.TABLE_PREFIX.'djg_pollsq q LEFT JOIN '.TABLE_PREFIX.'djg_pollsa a ON (q.pollq_id = a.polla_qid) WHERE q.pollq_id = '.$questionId);
      $s1 = $s1q->fetchAll();    
      // $title = $q[0]['pollq_question']; 
      //$total = $q[0]['pollq_totalvotes']; 
    else:
      $s1 = array();
    endif;
    */
    
    $this->display('djg_poll/views/statistics', array('polls' => $polls, 'qsa'=>$qsa, 'asa'=>$asa, 'error'=>$error));
  }
	public function documentation() {
		$content = Parsedown::instance()->parse(file_get_contents(PLUGINS_ROOT.DS.'djg_poll'.DS.'README.md'));
        $this->display('djg_poll/views/documentation', array('content'=>$content));
    }
    function settings() {
        $this->display('djg_poll/views/settings', array('settings' => Plugin::getAllSettings('djg_poll')));
    }
    function save() {
        if (isset($_POST['settings'])):
            $settings = $_POST['settings'];
            foreach ($settings as $key => $value) $settings[$key] = mysql_escape_string($value);
            if (Plugin::setAllSettings($settings, 'djg_poll'))
                Flash::set('success', __('The settings have been saved.'));
            else
                Flash::set('error', __('An error occured trying to save the settings.'));
        else:
            Flash::set('error', __('Could not save settings, no settings found.'));
        endif;
        redirect(get_url('plugin/djg_poll/settings'));
    }
	function delete($id, $page_id=0) {
		if (Djgpoll::delPoll($id)) Flash::set('success',__('Poll was deleted!'));
		($page_id!=0) ? redirect(get_url('plugin/djg_poll/polls/'.$page_id)) : redirect(get_url('plugin/djg_poll/polls'));
	}
  /**
  * change poll status active / inactive
  */
	function onOff($id, $page_id=0) {
    $result = Djgpoll::onOffPoll($id);
		if ($result===false) 
      Flash::set('error',__('Status has not been changed.'));
    elseif($result==0)
      Flash::set('success',__('Poll (:id) is :status now!',array(':id'=>$id,':status'=>'active')));
    elseif($result==1)
      Flash::set('success',__('Poll (:id) is :status now!',array(':id'=>$id,':status'=>'inactive')));
		($page_id!=0) ? redirect(get_url('plugin/djg_poll/polls/'.$page_id)) : redirect(get_url('plugin/djg_poll/polls'));
	}
	function djg_poll_chart($d1,$d2,$id,$kind){
		$__CMS_CONN__ = Record::getConnection();
		$chSize = explode('x',Plugin::getSetting('chartsSize','djg_poll'));
		$cfg['width'] = $chSize[0];
		$cfg['height'] = $chSize[1];
		switch ($kind) {
		case 'votesPerDay':
			$s1q = $__CMS_CONN__->query('SELECT q.pollq_id, q.pollq_question, COUNT(i.pollip_id) as votes, DATE(i.pollip_timestamp) AS date 
			FROM '.TABLE_PREFIX.'djg_pollsip i
			LEFT JOIN '.TABLE_PREFIX.'djg_pollsq q
			ON (i.pollip_qid = q.pollq_id)
			WHERE q.pollq_id = '.$id.'
			AND
			DATE(i.pollip_timestamp) between "'.$d1.'" and "'.$d2.'"
			GROUP BY DAY(i.pollip_timestamp) 
			ORDER BY i.pollip_timestamp ASC');
			while ($arr = $s1q->fetch()) $data[$arr['date']] = $arr['votes'];
			if (empty($data)) $data[__('no results')] = 0;
			$graph = new phpMyGraph();
			$graph->parseVerticalLineGraph($data, $cfg);
			break;
		}
		exit();	
	}
	/*
	* AJAX
	*/
	function djg_poll_ajax_vote(){
		if( (!isset($_POST['answare_id'])) || (!isset($_POST['question_id'])) ):
			$json['error'] = 1;
		else:
			$json['error'] = !Djgpoll::addVote($_POST['question_id'],$_POST['answare_id']);
			$json['results'] = Djgpoll::renderPollResults((int)$_POST['question_id'],$_POST['answare_id']);
		endif;
		echo json_encode($json);
		exit();	
	}
	/** 
	* Dispatcher
	*/
	public function djg_poll_frontend_assets(){
	header("Content-type: application/x-javascript");
	?>
	function animateResults(div){
		$(div+' .bar').each(function(){
			var percentage = $(this).css('width');
			$(this).css({width: "0%"}).animate({width: percentage}, 'slow');
		});
	};
	function showAlert(div,text){
		div.html(text);
		div.delay(400).fadeOut(400, function () {
			$(this).html('');
			$(this).fadeIn(1);
		});
	};
	if (typeof(jQuery) != 'undefined') {
		$(function() 
		{
			animateResults('.djg_poll_result_area');
			$('<link rel="stylesheet" type="text/css" href="<?php echo URL_PUBLIC; ?>wolf/plugins/djg_poll/assets/djg_poll_frontend.css" />').appendTo('head');
			$("input[type='button']").click(function() 
			{
				if(($(this).parent().attr('class') == 'djg_poll_vote_area') && ($(this).attr('name') == 'vote') ){
					var area = $(this).parent();
          var alert = area.find('.djg_poll_alert');
					var question_id = area.attr("id").match(/[\d]+$/);
					var checked = new Array();
          var aCount = 0;
					$("input[name='djg_poll_q_"+question_id+"']:checked").each(function(i){checked.push(this.value);});
          $("input[name='djg_poll_q_"+question_id+"']").each(function(i){aCount++;});
					var new_checked = { 'answare_id[]': checked, 'question_id': question_id[0] };
          if( (checked.length == aCount) && (0 == <?php echo Plugin::getSetting('allowSelectAll','djg_poll'); ?>) ){
            showAlert(alert,'<?php echo __('You can not select all answares.'); ?>');
          }
          else if(checked.length > 0){  
            area.find('.djg_poll_vote_button').fadeOut(100);
            var jqxhr = $.post("<?php echo rtrim(URL_PUBLIC,'/').(USE_MOD_REWRITE ? '/': '/?/'); ?>djg_poll_vote.php", new_checked, function(data){}, "json");
            jqxhr.success(function(data){
              area.fadeOut(100, function () {
				area.html(data.results);
				area.fadeIn(100);
				animateResults('.djg_poll_result_area');
               });
            });
            jqxhr.complete(function(data){});
            jqxhr.error(function(data){
              showAlert(alert,'ajax error');
              area.find('.djg_poll_vote_button').fadeIn(100);
            });
          }else{
            showAlert(alert,'<?php echo __('Select answare first!'); ?>')
          };
				};
			});    
		});
	}else{
		alert('<?php echo __('no jQuery librery'); ?>');
	};
	<?php
	} // end djg_poll_frontend_assets function
}