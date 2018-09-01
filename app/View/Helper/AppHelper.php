<?php
App::uses('Helper', 'View');
class AppHelper extends Helper {


	/**
	 * エンティティ内の値をタイプに合わせて加工して表示。
	 *
	 * @param $ent エンティティ
	 * @param $key エンティティのキー
	 * @param $type	0(省略):空対応のみ	1:XSSサニタイズ	2:金額表記	3:有無フラグ用	4:改行文字対応 5:長文字用 6:テキストエリア用
	 * @param $option:オプションデータ $typeの値によって意味が変わる
	 * @return 値
	 */
	public function ent_show_x($ent,$key,$type=null,$option=array()){

		$v = ( isset($ent[$key]) ) ? $ent[$key] : null;

		if(!empty($option)){
			if(!empty($option[$v])){
				$v= $option[$v];
			}else{
				$v=null;
			}
		}


		switch ($type) {
			case null:
				break;

			case CB_FLD_SANITAIZE://サニタイズ
				$v=h($v);
				break;

			case CB_FLD_MONEY://金額表記

				if(!empty($v) || $v===0){
					$v= '&yen'.number_format($v);
				}

				break;

			case CB_FLD_DELETE_FLG://有無フラグ

				if($v==0){
					$v="<span style='color:#23d6e4;'>有効</span>";
				}elseif($ent['delete_flg']==1){
					$v="<span style='color:#b4b4b4;'>削除</span>";
				}
				break;

			case CB_FLD_BR://改行対応
				if(empty($v)){break;}

				$v= str_replace('\\r\\n', '<br>', h($v));
				$v= str_replace('\\', '', $v);
				break;

			case CB_FLD_BOUTOU://長文字用。テキストエリアなど長文字を指定文字数分表示。

				if(empty($v)){break;}

				$strLen=20;//表示文字数
				if(!empty($option)){
					$strLen=$option;
				}
				$v=mb_strimwidth($v, 0, $strLen, "...");
				$v= str_replace('\\r\\n', ' ', h($v));
				$v= str_replace('\\', '', $v);

				break;

			case CB_FLD_TEXTAREA://テキストエリア用（改行対応）
				if(empty($v)){break;}

				$v = str_replace('\\r\\n', '&#13;', h($ent[$key]));//サニタイズされた改行コードを「&#13;」に置換
				$v = str_replace('\\', '', $v);

				break;

			default:
				break;
		}

		return $v;

	}


	/**
	 * ラベル名と値からなる要素を作成
	 * ent_show_xの拡張メソッド
	 */
	function ent_show_preview($label_name,$ent,$key,$type=null,$option=array()){

		$value=$this->ent_show_x($ent,$key,$type,$option);
		$h="
			<div class='field_a'>
			<div class='field_a_label'>{$label_name}</div>
			<div class='field_a_value'>{$value}</div>
			</div>
			<div style='clear:both'></div>";

		return $h;

	}






	/**
	 * 配列からdatalistを作成する。
	 * @param  $id ID属性
	 * @param  $list datalist内に表示するオプション
	 * @return datalist要素のHTML
	 */
	function datalist($id,$list){

		$str_options='';
		foreach($list as $v){
			$str_options.="<option value='{$v}'>";
		}

		$html="<datalist id='{$id}'>{$str_options}</datalist>";

		return $html;

	}







}