<?php
App::uses('Model', 'Model');
App::uses('CrudBase', 'Model');

/**
 * 絶滅危惧生物のCakePHPモデルクラス
 *
 * @date 2015-9-16 | 2018-8-30
 * @version 3.0.4
 *
 */
class EnSp extends AppModel {

	public $name='EnSp';
	
	// 関連付けるテーブル CBBXS-1040
	public $useTable = 'en_sps';

	// CBBXE


	/// バリデーションはコントローラクラスで定義
	public $validate = null;
	
	
	public function __construct() {
		parent::__construct();
		
		// CrudBaseロジッククラスの生成
		if(empty($this->CrudBase)) $this->CrudBase = new CrudBase();
	}
	
	/**
	 * 絶滅危惧生物エンティティを取得
	 *
	 * 絶滅危惧生物テーブルからidに紐づくエンティティを取得します。
	 *
	 * @param int $id 絶滅危惧生物ID
	 * @return array 絶滅危惧生物エンティティ
	 */
	public function findEntity($id){

		$conditions='id = '.$id;

		//DBからデータを取得
		$data = $this->find(
				'first',
				Array(
						'conditions' => $conditions,
				)
		);

		$ent=array();
		if(!empty($data)){
			$ent=$data['EnSp'];
		}
		



		return $ent;
	}


	
	
	/**
	 * 一覧データを取得する
	 * @return array 絶滅危惧生物画面一覧のデータ
	 */
	public function findData(&$crudBaseData){

		$kjs = $crudBaseData['kjs'];//検索条件情報
		$pages = $crudBaseData['pages'];//ページネーション情報


		$page_no = $pages['page_no']; // ページ番号
		$row_limit = $pages['row_limit']; // 表示件数
		$sort_field = $pages['sort_field']; // ソートフィールド
		$sort_desc = $pages['sort_desc']; // ソートタイプ 0:昇順 , 1:降順
		
		
		//条件を作成
		$conditions=$this->createKjConditions($kjs);
		
		// オフセットの組み立て
		$offset=null;
		if(!empty($row_limit)) $offset = $page_no * $row_limit;
		
		// ORDER文の組み立て
		$order = $sort_field;
		if(empty($order)) $order='sort_no';
		if(!empty($sort_desc)) $order .= ' DESC';
		
		$option=array(
				'conditions' => $conditions,
				'limit' =>$row_limit,
				'offset'=>$offset,
				'order' => $order,
		);
		
		//DBからデータを取得
		$data = $this->find('all',$option);
		
		//データ構造を変換（2次元配列化）
		$data2=array();
		foreach($data as $i=>$tbl){
			foreach($tbl as $ent){
				foreach($ent as $key => $v){
					$data2[$i][$key]=$v;
				}
			}
		}
		
		return $data2;
	}

	
	
	/**
	 * SQLのダンプ
	 * @param  $option
	 */
	private function dumpSql($option){
		$dbo = $this->getDataSource();
		
		$option['table']=$dbo->fullTableName($this->EnSp);
		$option['alias']='EnSp';
		
		$query = $dbo->buildStatement($option,$this->EnSp);
		
		Debugger::dump($query);
	}



	/**
	 * 検索条件情報からWHERE情報を作成。
	 * @param array $kjs	検索条件情報
	 * @return string WHERE情報
	 */
	private function createKjConditions($kjs){

		$cnds=null;
		
		$this->CrudBase->sql_sanitize($kjs); // SQLサニタイズ
		
		if(!empty($kjs['kj_main'])){
			$cnds[]="CONCAT( IFNULL(EnSp.family_name, '') ,IFNULL(EnSp.wamei, '') ,IFNULL(EnSp.scien_name, '') ,IFNULL(EnSp.note, '')) LIKE '%{$kjs['kj_main']}%'";
		}
		
		// CBBXS-1003
		if(!empty($kjs['kj_id']) || $kjs['kj_id'] ==='0' || $kjs['kj_id'] ===0){
			$cnds[]="EnSp.id = {$kjs['kj_id']}";
		}
		if(!empty($kjs['kj_bio_cls_id']) || $kjs['kj_bio_cls_id'] ==='0' || $kjs['kj_bio_cls_id'] ===0){
			$cnds[]="EnSp.bio_cls_id = {$kjs['kj_bio_cls_id']}";
		}
		if(!empty($kjs['kj_family_name'])){
			$cnds[]="EnSp.family_name LIKE '%{$kjs['kj_family_name']}%'";
		}
		if(!empty($kjs['kj_wamei'])){
			$cnds[]="EnSp.wamei LIKE '%{$kjs['kj_wamei']}%'";
		}
		if(!empty($kjs['kj_scien_name'])){
			$cnds[]="EnSp.scien_name LIKE '%{$kjs['kj_scien_name']}%'";
		}
		if(!empty($kjs['kj_en_ctg_id']) || $kjs['kj_en_ctg_id'] ==='0' || $kjs['kj_en_ctg_id'] ===0){
			$cnds[]="EnSp.en_ctg_id = {$kjs['kj_en_ctg_id']}";
		}
		if(!empty($kjs['kj_endemic_sp_flg'])){
			$cnds[]="EnSp.endemic_sp_flg = {$kjs['kj_endemic_sp_flg']}";
		}
		if(!empty($kjs['kj_note'])){
			$cnds[]="EnSp.note LIKE '%{$kjs['kj_note']}%'";
		}
		if(!empty($kjs['kj_sort_no']) || $kjs['kj_sort_no'] ==='0' || $kjs['kj_sort_no'] ===0){
			$cnds[]="EnSp.sort_no = {$kjs['kj_sort_no']}";
		}
		$kj_delete_flg = $kjs['kj_delete_flg'];
		if(!empty($kjs['kj_delete_flg']) || $kjs['kj_delete_flg'] ==='0' || $kjs['kj_delete_flg'] ===0){
			if($kjs['kj_delete_flg'] != -1){
			   $cnds[]="EnSp.delete_flg = {$kjs['kj_delete_flg']}";
			}
		}
		if(!empty($kjs['kj_update_user'])){
			$cnds[]="EnSp.update_user LIKE '%{$kjs['kj_update_user']}%'";
		}
		if(!empty($kjs['kj_ip_addr'])){
			$cnds[]="EnSp.ip_addr LIKE '%{$kjs['kj_ip_addr']}%'";
		}
		if(!empty($kjs['kj_created'])){
			$kj_created=$kjs['kj_created'].' 00:00:00';
			$cnds[]="EnSp.created >= '{$kj_created}'";
		}
		if(!empty($kjs['kj_modified'])){
			$kj_modified=$kjs['kj_modified'].' 00:00:00';
			$cnds[]="EnSp.modified >= '{$kj_modified}'";
		}

		// CBBXE
		
		$cnd=null;
		if(!empty($cnds)){
			$cnd=implode(' AND ',$cnds);
		}

		return $cnd;

	}

	/**
	 * エンティティをDB保存
	 *
	 * 絶滅危惧生物エンティティを絶滅危惧生物テーブルに保存します。
	 *
	 * @param array $ent 絶滅危惧生物エンティティ
	 * @param array $option オプション
	 *  - form_type フォーム種別  new_inp:新規入力 , copy:複製 , edit:編集
	 *  - ni_tr_place 新規入力追加場所フラグ 0:末尾 , 1:先頭
	 * @return array 絶滅危惧生物エンティティ（saveメソッドのレスポンス）
	 */
	public function saveEntity($ent,$option=array()){

		// 新規入力であるなら新しい順番をエンティティにセットする。
		if($option['form_type']=='new_inp' ){
			if(empty($option['ni_tr_place'])){
				$ent['sort_no'] = $this->CrudBase->getLastSortNo($this); // 末尾順番を取得する
			}else{
				$ent['sort_no'] = $this->CrudBase->getFirstSortNo($this); // 先頭順番を取得する
			}
		}
		

		//DBに登録('atomic' => false　トランザクションなし。saveでSQLサニタイズされる）
		$ent = $this->save($ent, array('atomic' => false,'validate'=>false));

		//DBからエンティティを取得
		$ent = $this->find('first',
				array(
						'conditions' => "id={$ent['EnSp']['id']}"
				));

		$ent=$ent['EnSp'];
		if(empty($ent['delete_flg'])) $ent['delete_flg'] = 0;

		return $ent;
	}

	


	/**
	 * 全データ件数を取得
	 *
	 * limitによる制限をとりはらった、検索条件に紐づく件数を取得します。
	 *  全データ件数はページネーション生成のために使われています。
	 *
	 * @param array $kjs 検索条件情報
	 * @return int 全データ件数
	 */
	public function findDataCnt($kjs){

		//DBから取得するフィールド
		$fields=array('COUNT(id) AS cnt');
		$conditions=$this->createKjConditions($kjs);

		//DBからデータを取得
		$data = $this->find(
				'first',
				Array(
						'fields'=>$fields,
						'conditions' => $conditions,
				)
		);

		$cnt=$data[0]['cnt'];
		return $cnt;
	}
	
	
	// CBBXS-1021
	/**
	 * 綱IDリストをDBから取得する
	 */
	public function getBioClsIdList(){
		if(empty($this->BioCls)){
			App::uses('BioCls','Model');
			$this->BioCls=ClassRegistry::init('BioCls');
		}
		$fields=array('id','bio_cls_name');//SELECT情報
		$conditions=array("delete_flg = 0");//WHERE情報
		$order=array('sort_no');//ORDER情報
		$option=array(
				'fields'=>$fields,
				'conditions'=>$conditions,
				'order'=>$order,
		);

		$data=$this->BioCls->find('all',$option); // DBから取得
		
		// 構造変換
		if(!empty($data)){
			$data = Hash::combine($data, '{n}.BioCls.id','{n}.BioCls.bio_cls_name');
		}
		
		return $data;
	}
	/**
	 * 絶滅危惧種カテゴリーIDリストをDBから取得する
	 */
	public function getEnCtgIdList(){
		if(empty($this->EnCtg)){
			App::uses('EnCtg','Model');
			$this->EnCtg=ClassRegistry::init('EnCtg');
		}
		$fields=array('id','en_ctg_name');//SELECT情報
		$conditions=array("delete_flg = 0");//WHERE情報
		$order=array('sort_no');//ORDER情報
		$option=array(
				'fields'=>$fields,
				'conditions'=>$conditions,
				'order'=>$order,
		);

		$data=$this->EnCtg->find('all',$option); // DBから取得
		
		// 構造変換
		if(!empty($data)){
			$data = Hash::combine($data, '{n}.EnCtg.id','{n}.EnCtg.en_ctg_name');
		}
		
		return $data;
	}

	// CBBXE


}