<?php
class Djgpoll {

	function Djgpoll(){
		// constructor;
	}
	/** DONE
	* insert new poll
	* parametr newPoll (Array)
	* return false or last poll id
	*/
   public static function addNewPoll($newPoll) {
    $__CMS_CONN__ = Record::getConnection();
    $pollq = $__CMS_CONN__->prepare('INSERT INTO '.TABLE_PREFIX.'djg_pollsq(pollq_question, pollq_active, pollq_multiple, pollq_timestamp, pollq_date, pollq_startvote, pollq_endvote) VALUES(:question, :active, :multiple, :timestamp, now(), :startvote, :endvote)');
    $polla = $__CMS_CONN__->prepare('INSERT INTO '.TABLE_PREFIX.'djg_pollsa(polla_qid, polla_answers) VALUES(:polla_qid, :polla_answers)');
	try {
		$__CMS_CONN__->beginTransaction();
		/* q */
		$pollq->bindValue(':question', $newPoll['q']);
		$pollq->bindValue(':active', $newPoll['active']);
		$pollq->bindValue(':multiple', $newPoll['multiple']);
		$pollq->bindValue(':timestamp', $newPoll['timestamp']);
		$pollq->bindValue(':startvote', $newPoll['startvote'], PDO::PARAM_STR);
		$pollq->bindValue(':endvote', $newPoll['endvote'], PDO::PARAM_STR);
		$pollq->execute();
		$lastId = $__CMS_CONN__->lastInsertId();
		/* a */
          for($i=0; $i < count($newPoll['a']); $i++):
              $polla->bindValue('polla_qid', $lastId);
              $polla->bindValue(':polla_answers', $newPoll['a'][$i]);
              usleep(200);
              $polla->execute();
          endfor;
          usleep(200);
          $__CMS_CONN__->commit();
      } 
      catch(PDOException $e) 
      {
          if(stripos($e->getMessage(), 'DATABASE IS LOCKED') !== false):
              // This should be specific to SQLite, sleep for 0.25 seconds
              // and try again.  We do have to commit the open transaction first though
              $__CMS_CONN__->commit();
              usleep(200);
              return false;      
          else:
              $__CMS_CONN__->rollBack();
              return false;
          endif;
      }
      return $lastId;
    }
    /** DONE
    * edit poll
    * parametr poll_id (int)
    * return true or false
    */
	public static function editPoll($data) {
		$__CMS_CONN__ = Record::getConnection();
		$pollq1 = $__CMS_CONN__->prepare('UPDATE '.TABLE_PREFIX.'djg_pollsq SET pollq_multiple = :multiple, pollq_active = :active, pollq_timestamp = :timestamp, pollq_startvote = :startvote, pollq_endvote = :endvote WHERE pollq_id = :questionId');
		$pollq1->bindValue(':multiple', $data['multiple']);
		$pollq1->bindValue(':active', $data['active']);
		$pollq1->bindValue(':timestamp', $data['timestamp']);
		$pollq1->bindValue(':questionId', $data['questionId']);
		$pollq1->bindValue(':startvote', $data['startvote'], PDO::PARAM_STR);
		$pollq1->bindValue(':endvote', $data['endvote'], PDO::PARAM_STR);
		if ($pollq1->execute())
		  return true;
		else
		  return false; 
  }
    /** DONE
    * delPoll
    * parametr poll_id (int)
    * return true or false
    */
	public static function delPoll($questionId) {
		$__CMS_CONN__ = Record::getConnection();
		$pollq = $__CMS_CONN__->prepare('DELETE FROM '.TABLE_PREFIX.'djg_pollsq WHERE pollq_id = :pollq_id LIMIT 1');
		$polla = $__CMS_CONN__->prepare('DELETE FROM '.TABLE_PREFIX.'djg_pollsa WHERE polla_qid = :pollq_id');
    $pollip = $__CMS_CONN__->prepare('DELETE FROM '.TABLE_PREFIX.'djg_pollsip WHERE pollip_qid = :pollq_id');
		try {
			$__CMS_CONN__->beginTransaction();
			$pollq->bindValue(':pollq_id', $questionId);
			$pollq->execute();
			usleep(200);
			$polla->bindValue(':pollq_id', $questionId);
			$polla->execute();
			usleep(200);
			$pollip->bindValue(':pollq_id', $questionId);
			$pollip->execute();
			usleep(200);
			$__CMS_CONN__->commit();
      return true;
		} 
		catch(PDOException $e) 
		{
			$__CMS_CONN__->rollBack(); return false;
		}
	}
    /** DONE
    * change poll status active/inactive
    * parametr questionId (int)
    * return false or new status (int)
    */
	public static function onOffPoll($questionId) {
		$__CMS_CONN__ = Record::getConnection();
		//get current status
		$pollsq = $__CMS_CONN__->query('SELECT pollq_active FROM '.TABLE_PREFIX.'djg_pollsq WHERE pollq_id = '.$questionId.' LIMIT 1');
		$q = $pollsq->fetchAll();
		$newStatus = ($q[0]['pollq_active']==1)?'0':'1';
		//set new status
		$pollq1 = $__CMS_CONN__->prepare('UPDATE '.TABLE_PREFIX.'djg_pollsq SET pollq_active = :newStatus WHERE pollq_id = :questionId');
		$pollq1->bindValue(':newStatus', $newStatus);
		$pollq1->bindValue(':questionId', $questionId);
		if ($pollq1->execute())
			return $newStatus;
		else
		return false; 
  }
    //set new status
    /** DONE
    * addVotes
    * parametr question id (int), answare id (array)
    * return true or false
    */
    public static function addVote($questionId=null,$answareId=null){
      if ( (!is_array($answareId)) or (count($answareId)==0) or ($questionId==null) or ($answareId==null)): 
        return false; 
      else:
        $ip = $_SERVER['REMOTE_ADDR'];
        $host = $_SERVER['SERVER_NAME'];
        //AuthUser::load();
		$userName = (AuthUser::isLoggedIn())?AuthUser::getUserName():'noUser';
		$userId = (AuthUser::isLoggedIn())?AuthUser::getId():'0';
        //
        $__CMS_CONN__ = Record::getConnection();
        $pollip = $__CMS_CONN__->prepare('INSERT INTO '.TABLE_PREFIX.'djg_pollsip (pollip_qid, pollip_aid, pollip_ip, pollip_host, pollip_timestamp, pollip_user, pollip_userid) VALUES(:pollip_qid, :pollip_aid, :pollip_ip, :pollip_host, now(), :pollip_user, :pollip_userid)');
        $polla = $__CMS_CONN__->prepare('UPDATE '.TABLE_PREFIX.'djg_pollsa SET polla_votes = polla_votes + 1 WHERE polla_aid = :answareId');
        $pollq = $__CMS_CONN__->prepare('UPDATE '.TABLE_PREFIX.'djg_pollsq SET pollq_totalvoters = pollq_totalvoters + 1, pollq_totalvotes = pollq_totalvotes+:pollq_totalvotes WHERE pollq_id = :questionId');
        //
        try {
          $__CMS_CONN__->beginTransaction();
          for($i=0; $i < count($answareId); $i++):
            //ip
            $pollip->bindValue(':pollip_qid', $questionId);
            $pollip->bindValue(':pollip_aid', $answareId[$i]);
            $pollip->bindValue(':pollip_ip', $ip);
            $pollip->bindValue(':pollip_host', $host);
            $pollip->bindValue(':pollip_user', $userName);
            $pollip->bindValue(':pollip_userid', $userId);
            $polla->bindValue(':answareId', $answareId[$i]);
            $polla->execute();
			$pollip->execute();
            usleep(200);
          endfor;
          //question
          $pollq->bindValue(':questionId', $questionId);
          $pollq->bindValue(':pollq_totalvotes', count($answareId));
          $pollq->execute();
          $__CMS_CONN__->commit();
          self::setcookie($questionId);
          return true;
        } 
        catch(PDOException $e) 
        {
          $__CMS_CONN__->rollBack(); return false;
        }
     endif;
    }
	/** DONE
	* setCookie
	* parametr questionId id (int)
	* return true or false
	*/
    public static function setCookie($questionId=null){
		//get timestamp
		$__CMS_CONN__ = Record::getConnection();
		$pollsq = $__CMS_CONN__->query('SELECT pollq_timestamp FROM '.TABLE_PREFIX.'djg_pollsq WHERE pollq_id = '.$questionId.' LIMIT 1');
		$q = $pollsq->fetchAll();
		if(setcookie(md5("djg_poll_cookie_".$questionId),1,time()+3600*$q[0]['pollq_timestamp']))
			return true;
		else
			return false;
	}
	/** DONE
	* checkCookie
	* parametr questionId id (int)
	* return true or false
	*/
	public static function checkCookie ($questionId=null){
		if ( isset($_COOKIE[md5("djg_poll_cookie_".$questionId)]) && ($questionId!=null) && ($_COOKIE[md5("djg_poll_cookie_".$questionId)] == 1)	)
			return true;
		else
			return false;
	}
	/** DONE
	* checkIP
	* parametr questionId id (int)
	* return true or false
  * if string can't vote
  * if false can vote
	*/
	public static function checkIP ($questionId=null){
    $__CMS_CONN__ = Record::getConnection();
		$pollsip = $__CMS_CONN__->query('SELECT  pollip_id, pollip_qid, pollip_ip, pollip_timestamp FROM '.TABLE_PREFIX.'djg_pollsip WHERE pollip_qid = '.$questionId.' AND pollip_ip = "'.$_SERVER['REMOTE_ADDR'].'" GROUP BY pollip_timestamp ORDER BY pollip_timestamp DESC LIMIT 1');
    $ipq = $pollsip->fetchAll();
    if ( ( count($ipq)>0 ) && ( (self::roznica_data($ipq[0]['pollip_timestamp'],date('Y-m-d H:i:s'),"hours")) < (self::checkTimestamp($questionId)) ) ):
       return true;
    else:
      return false;
    endif;
	}
    /**
    * AJAX JSON
    * make a vote
    * $g - integer (question id)
    * $a - integer or array (answares ids)
    * return true or alert
    */
    public static function vote($g=null,$a=null) {
      if ($q==null):
        return __('no question id');
      elseif ($a==null):
        return __('no answare id');
      endif;
    }
    /* RENDER */
	
	/** DODAĆ WARUNEK
	* renderPollForm
	* parametr questionId id (int)
	* return html string
	*/
    /**
    * render form
    * return string
    */
    public static function renderPollForm($questionId) {
      $return = self::canVote($questionId);
      if($return['error'] == 1):
        $resultHtml[] = self::renderPollResults($questionId);
        $resultHtml[] = $return['alert'];
      else:
        $resultHtml[] = '<div class="djg_poll_vote_area" id="djg_poll-id-'.$questionId.'">';
        $__CMS_CONN__ = Record::getConnection();
        $pollsq = $__CMS_CONN__->query('SELECT pollq_id, pollq_multiple, pollq_question, pollq_totalvotes FROM '.TABLE_PREFIX.'djg_pollsq WHERE pollq_id = '.$questionId.' LIMIT 1');
        $q = $pollsq->fetchAll();
        $input_type = ($q[0]['pollq_multiple']==1)?'checkbox':'radio';
        $pollq_totalvotes = $q[0]['pollq_totalvotes'];  
        $pollsa = $__CMS_CONN__->query('SELECT * FROM '.TABLE_PREFIX.'djg_pollsa WHERE polla_qid = '.$questionId.' ORDER BY polla_aid ASC');
        $resultHtml[] = '<h3>'.$q[0]['pollq_question'].'</h3>';
        $resultHtml[] = '<ul style="margin: 0;">';
        foreach ($pollsa as $row):
          $resultHtml[] = '<li style="list-style: none;"><input type="'.$input_type.'" name="djg_poll_q_'.$questionId.'" value="'.$row['polla_aid'].'" /><span>'.$row['polla_answers'].'</span></li>';
        endforeach;
        $resultHtml[] = '</ul>';
        $resultHtml[] = '<input type="button" name="vote" class="djg_poll_vote_button" value="'.__('vote').'" />';
        $resultHtml[] = '<div class="djg_poll_alert"></div>';
        $resultHtml[] = '</div>';
      endif;
      return implode("", $resultHtml);
    } // end renderPollForm result
    
    /**
    * render result
    * $questionId int
    * $answareId array
    * return string
    */
    public static function renderPollResults($questionId=null,$answareId=null) {
      $__CMS_CONN__ = Record::getConnection();
      $order = (Plugin::getSetting('sortResults','djg_poll') == 1)?'ORDER BY a.polla_votes DESC':'';
      $pollsq = $__CMS_CONN__->query('SELECT q.pollq_id, a.polla_aid, q.pollq_question, q.pollq_totalvotes, a.polla_answers,	a.polla_votes FROM '.TABLE_PREFIX.'djg_pollsq q LEFT JOIN '.TABLE_PREFIX.'djg_pollsa a ON (q.pollq_id = a.polla_qid) WHERE q.pollq_id = '.$questionId.' '.$order.' ');
      $q = $pollsq->fetchAll();    
      $title = $q[0]['pollq_question'];
      $total = $q[0]['pollq_totalvotes'];
      $resultHtml[] = '<div class="djg_poll_result_area">';
      $resultHtml[] = '<h3>'.$title.'</h3>';
      foreach ($q as $row):
        $yourBar = ( ($answareId) && (in_array($row['polla_aid'], $answareId)) && (Plugin::getSetting('specifyYourVote','djg_poll')==1) )?'yourBar':'';
		$percent = ($total>0)?round(($row['polla_votes']*100)/$total):0;
        $resultHtml[] = '<div class="option" ><p>'.$row['polla_answers'].' (<em>'.$percent.'%</em>)</p></div>';
        $resultHtml[] = '<div class="bar '.$yourBar.'" style="width: '.$percent.'%; " ></div>'; 
      endforeach;
      $resultHtml[] = '<p>'.__('Total Votes: :total',array(':total'=> $total)).'</p>';  
      $resultHtml[] = '</div>'; 
      return implode("", $resultHtml);
    }

    /* HELPERS */
    /**
    * DONE
    * chack is life
    * parametr question id
    * return true or false
	*/
    public static function isLive($questionId){
	
      $__CMS_CONN__ = Record::getConnection();
      $pollq = $__CMS_CONN__->query('SELECT pollq_id, pollq_startvote, pollq_endvote FROM '.TABLE_PREFIX.'djg_pollsq WHERE (pollq_id = '.$questionId.') AND ( pollq_startvote = "0000-00-00 00:00:00" OR pollq_endvote = "0000-00-00 00:00:00" OR (NOW()BETWEEN pollq_startvote AND pollq_endvote) ) LIMIT 1');
      $rows = $pollq->fetchAll(PDO::FETCH_ASSOC);
      return (count($rows)==1)?1:0;
    }
    /**
    * DONE
    * chack is active
    * parametr question id
    * return true or false
    */
    public static function isActive($questionId){
      $__CMS_CONN__ = Record::getConnection();
      $pollq = $__CMS_CONN__->query('SELECT pollq_active FROM '.TABLE_PREFIX.'djg_pollsq WHERE pollq_id = '.$questionId.' AND pollq_active = 1 LIMIT 1');
      $rows = $pollq->fetchAll(PDO::FETCH_ASSOC);
      return (count($rows)==1)?1:0;
    }
    /**
    * chack is archive
    * parametr question id
    * return true or false
    */
    public static function isArchive($questionId){
      return ( (self::isLive($questionId) === 0) || (self::isActive($questionId) === 0) )?1:0;
    }
    /**
    * DONE
    * checkTimestamp
    * parametr question id
    * return string (number of hours)
    */
    public static function checkTimestamp($questionId){
    	$__CMS_CONN__ = Record::getConnection();
        $pollsq = $__CMS_CONN__->query('SELECT pollq_timestamp FROM '.TABLE_PREFIX.'djg_pollsq WHERE pollq_id = '.$questionId.' LIMIT 1');
        $q = $pollsq->fetchAll();
        return $q[0]['pollq_timestamp'];
    }
    /**
    * DONE
    * chack canVote
    * parametr question id
    * return true or false
    */
    public static function canVote($questionId){
      $return['error'] = 1;
      if (!self::isLive($questionId)):
        $return['alert'] = __('Poll is beyond the lifetime.');	  
      elseif (!self::isActive($questionId)):
        $return['alert'] = __('Poll is not active.');
      elseif( (self::checkIP($questionId) != false) && (Plugin::getSetting('checkIP','djg_poll')) ):
        $return['alert'] = __('IP');
      elseif( (self::checkCookie($questionId) != false) && (Plugin::getSetting('checkCookie','djg_poll')) ):
        $return['alert'] = __('Cookie');
      else:
        $return['error'] = 0;
      endif;
      return $return;
    }
   
    /**
    * DONE
    * return time between dates
    * example: echo roznica_data("2008-10-1", "2008-10-2", "hours"); // return 24
    * example: echo roznica_data("2008-10-1 01:12:34", "2008-10-2 02:00:00", "hours"); // return 25
    */
    public static function roznica_data($data_poczatek, $date_koniec, $jednostka_czasu="second")
    {
      $tablica = array('minutes'=>60, 'hours'=>3600, 'days'=>86400, 'second'=>1);
      return round(((strtotime($date_koniec) - strtotime($data_poczatek)) / $tablica[$jednostka_czasu]));
    }
	/**
	 * trims text to a space then adds ellipses if desired
	 * @param string $input text to trim
	 * @param int $length in characters to trim to
	 * @param bool $ellipses if ellipses (...) are to be added
	 * @param bool $strip_html if html tags are to be stripped
	 * @return string 
	 */
	public static function trim_text($input, $length = 50, $ellipses = true, $strip_html = true) {
		//strip tags, if desired
		if ($strip_html) {
			$input = strip_tags($input);
		}
	  
		//no need to trim, already shorter than trim length
		if (strlen($input) <= $length) {
			return $input;
		}
	  
		//find last space within length
		$last_space = strrpos(substr($input, 0, $length), ' ');
		$trimmed_text = substr($input, 0, $last_space);
	  
		//add ellipses (...)
		if ($ellipses) {
			$trimmed_text .= '...';
		}
	  
		return $trimmed_text;
	}
} // end Plugin class
