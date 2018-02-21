<?php
/**
 * ��Ա�ӿ�
 *
 */
class member_point {
	//���ݿ�����
	private $db;
	public function __construct() {
		$this->db = dig_base::load_model('member_model');
	}
	
	/**
	 * ��ȡ�û���Ϣ
	 * @param $username �û���
	 * @param $type {1:�û�id;2:�û���;3:email}
	 * @return $mix {-1:�û�������;userinfo:�û���Ϣ}
	 */
	public function get_member_info($mix, $type=1) {
		$mix = safe_replace($mix);
		if($type==1) {
			$userinfo = $this->db->get_one(array('userid'=>$mix));
		} elseif($type==2) {
			$userinfo = $this->db->get_one(array('username'=>$mix));
		} elseif($type==3) {
			if(!$this->_is_email($mix)) {
				return -4;
			}
			$userinfo = $this->db->get_one(array('email'=>$mix));
		}
		if($userinfo) {
			return $userinfo;
		} else {
			return -1;
		}
	}
	

	/**
	 * ����uid�����û�����
	 * @param int $userid	�û�id
	 * @param int $point	����
	 * @return boolean
	 */
	public function add_point($userid, $point) {
		$point = intval($point);
		$r = $this->db->get_one(array('userid'=>$userid));
		$oriPoint = $r['point'];
		$newPoint = $oriPoint+$point;

		$this->db->update(array('point'=>"+=$point"), array('userid'=>$userid));
        if($newPoint>=4000){
        	$this->db->update(array('groupid'=>3), array('userid'=>$userid));
        }
		return true;
	}

	/**
	 * ����uid�����û�����
	 * @param int $userid	�û�id
	 * @param int $point	����
	 * @return boolean
	 */
	public function del_point($userid, $point) {
		$point = intval($point);
		$r = $this->db->get_one(array('userid'=>$userid));
		$oriPoint = $r['point'];
		$newPoint = $oriPoint-$point;
		return $this->db->update(array('point'=>"-=$point"), array('userid'=>$userid));
		if($newPoint<4000){
        	$this->db->update(array('groupid'=>2), array('userid'=>$userid));
        }
	}
}