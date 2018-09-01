/**
 * CrudBase・ファイルアップロードコンポーネント | CrudBase File Upload Component
 * 
 * @note
 * CurdBase.jsのコンポーネントの一つ
 * 
 * @date 2018-8-24
 * @version 1.0
 * @history
 * 2018-8-24 開発
 * 
 */
class CbFileUploadComponent{
	
	
	/**
	 * コンストラクタ
	 * 
	 * @param param fuIds file要素idリスト
	 * 
	 */
	constructor(fuIds){
		
		
		this.fileUploadK = this._factoryFileUploadK(fuIds); // 拡張ファイルアップロード・オブジェクト
		
		this.fuIds = fuIds; // file要素idリスト
		this.fields = this._fueIdsToFields(fuIds); // FUフィールドリスト
		this.dpData = this._makeDtData(this.fields); // ディレクトリパス情報
		
	}
	
	/**
	 * ディレクトリパス情報
	 * @param array fuIds file要素idリスト
	 * @return array ディレクトリパス情報
	 */
	_makeDtData(fields){
		var dpData = {}; // ディレクトリパスデータ
		
		// ファイルアップロード用のディレクトリパステンプレート情報を取得
		var dpt_json = jQuery('#dpt_json').val();
		var dptData = JSON.parse(dpt_json);

		var dpTypes = ['orig','thum1','thum2']; // ディレクトリパスタイプリスト
		
		for(var dpt_i in dpTypes){
			var dp_type = dpTypes[dpt_i];
			var dp_tmpl = dptData[dp_type + '_dp_tmpl']; // ・ディレクトリパステンプレート

			for(var i in fields){
				var field = fields[i];
				var dp = dp_tmpl.replace('%field',field);
				if(dpData[field] == null) dpData[field] = {};
				dpData[field][dp_type] = dp;
			}
		}

		return dpData;
		
	}
	
	
	/**
	 * file要素idリストからフィールドリストを取得する
	 * @param array fuIds file要素idリスト
	 * @return array フィールドリスト
	 */
	_fueIdsToFields(fuIds){
		
		var fields = [];
		
		for(var i in fuIds){
			var fue_id = fuIds[i];
			var field = this._fudIdToField(fue_id); // file要素idからフィールドに変換する。
			fields.push(field);
		}
		
		fields = this._array_unique(fields); // 重複を除去する

		return fields;
	}
	
	/**
	 * file要素idからフィールドに変換する
	 * @param string fue_id file要素ID
	 * @return string フィールド
	 */
	_fudIdToField(fue_id){
		
		var field = fue_id; // フィールド
		if(fue_id == null) return field;
		if(fue_id.length < 3) return field;
		
		var es2 = field.slice(-2); // ディレクトリパスから末尾の2文字を取得する。
		
		
		if(es2 == '_n' || es2 == '_e'){
			var field = field.substr(0,field.length - 2);
		}
		
		return field;
	}
	
	
	/**
	* 配列から重複を除去する
	*/
	_array_unique(ary){
		var ary2= ary.filter(function (x, i, self) {
			return self.indexOf(x) === i;
		});
		
		return ary2;
	}
	


	/**
	 * 拡張ファイルアップロード・コンポーネントのファクトリーメソッド
	 * @param array fuIds file要素idリスト
	 * @return FileUploadK 拡張ファイルアップロード・コンポーネント
	 */
	_factoryFileUploadK(fuIds){
		
		// 拡張ファイルアップロードクラスの生成
		var fileUploadK = new FileUploadK({
			'prog_slt':'#prog1',
			'err_slt':'#err',
			'valid_ext':'image',
			'img_width':120,
			'img_height':120,
			});
		
		// file要素を拡張
		for(var i in fuIds){
			var fue_id = fuIds[i];
			fileUploadK.addEvent(fue_id);
		}
		
		
		
		return fileUploadK;
		
	}
	
	
	
	/**
	 * ファイルアップロード関連のイベントを追加する。
	 * 
	 * @note
	 * ファイルチェンジイベントの追加。
	 * DnDイベントの追加
	 * 
	 * @param fue_id ファイルアップロード要素のid属性
	 * @param option 
	 *  - valid_ext バリデーション拡張子(詳細はconstructor()の引数を参照）
	 *  - pacb プレビュー後コールバック関数
	 *  - img_width プレビュー画像サイスX　（画像ファイルのみ影響）
	 *  - img_height プレビュー画像サイスY
	 */
	addEvent(fue_id,option){
		return this.fileUploadK.addEvent(fue_id,option);
	}
	
	
	/**
	 * ファイル群のパラメータデータを取得する
	 * @return object ファイル群のパラメータデータ
	 */
	getFileParams(){
		return this.fileUploadK.getFileParams();
	}
	
	
	/**
	 * AJAXによるアップロード
	 * 
	 * @param callback(res) ファイルアップロード後コールバック
	 * @param withData 一緒に送るデータ
	 * @param option 未使用
	 */
	uploadByAjax(callback,withData,option){
		return this.fileUploadK.uploadByAjax(callback,withData,option);
	}
	
	
	/**
	 * ファイル名リストを取得
	 * @param int fue_id ファイル要素のID属性値（省略可）
	 * @return array ファイル名リスト
	 */
	getFileNames(fue_id){
		return this.fileUploadK.getFileNames(fue_id);
	}
	
	
	/**
	 * file要素にファイルパスをセットする
	 * @param string fue_id file要素のid
	 * @param array fn ファイル名リスト（ ファイル名一つを文字列指定可）
	 * @param object option addEventのoptionと同じ
	 */
	setFilePaths(fue_id,fns,option){
		
		if(typeof fns == 'string'){
			fns = [fns];
		}
		
		
		var field = this._fudIdToField(fue_id); // file要素idからフィールドに変換する。
		var dp = this.dpData[field]['orig']; // ディレクトリパスを取得

		// ファイルパスリストを組み立て
		var fps = [];
		for(var i in fns){
			var fn = fns[i];
			var fp = this._joinDpFn(dp,fn); // ディレクトリとファイル名を連結してファイルパスを作成
			fps.push(fp);
		}
		
		// file要素にファイルパスをセットする
		this.fileUploadK.setFilePaths(fue_id,fps,option);
		
		var res = {'fps':fps};
		
		return res;
		
		
	}
	
	
	/**
	 * ディレクトリとファイル名を連結してファイルパスを作成
	 * 
	 * @note
	 * ディレクトリパス末尾のセパレータ有無を自動判別してファイルパスを作成する。
	 * 2種類のセパレータ,「/」と「\」に対応している。
	 *
	 * @param dp ディレクトリパス
	 * @param fn ファイル名
	 * @returns string ファイルパス
	 */
	_joinDpFn(dp,fn){
		
		var fp = ''; // ファイルパス
		
		// ディレクトリパスが空であるならファイル名をファイルパスとして返す。
		if(dp == null || dp == '' || dp == 0) return fn;
	
		var end_str = dp.slice(-1); // ディレクトリパスから末尾の一文字を取得する。
		
		// 末尾の一文字がセパレータである場合
		if(end_str == '/' || end_str == '\\'){
			fp = dp + fn;
		}
		
		// 末尾の一文字がセパレータでない場合
		else{
			
			// セパレータを取得
			var sep = '/';
			var i = dp.lastIndexOf('\\');
			if(i >= 0) sep = '\\';
			
			fp = dp + sep + fn;
		}
		
	
		return fp;
	}
	
	
	/**
	 * 画像をTR要素に表示する
	 * @param jQuery tr TR要素オブジェクト
	 * @param object ent データエンティティ
	 */
	setImageToTr(tr,ent){

		console.log('test=setImageToTr');//■■■□□□■■■□□□■■■□□□)
		for(var i in this.fields){
			var field = this.fields[i];
			
			// TR要素からLabel要素を取得する。
			var lbl = tr.find("[for='" + field + "']");
			if(!lbl[0]) continue;
			
			var imgElm = lbl.find('img');
			if(imgElm[0]){
				
				var fn = ent[field]; // ファイル名
				var orig_dp = this.dpData[field]['orig']; // オリジナルディレクトリパス
				var orig_fp = this._joinDpFn(orig_dp,fn); // オリジナルファイルパス
				var thum_dp = this.dpData[field]['thum1']; // サムネイルディレクトリパス
				var thum_fp = this._joinDpFn(thum_dp,fn); // サムネイルファイルパス
				
				// IMG要素に画像を表示する。
				var imgObj = new Image();
				imgObj.src = thum_fp;
				imgObj.onload = () => {
					imgElm.attr('src',thum_fp);
				};
				
				// アンカー要素を取得し、オリジナルファイルパスをセットする。
				var aElm = lbl.find('a');
				if(aElm[0]){
					aElm.attr('href',orig_fp);
				}
				
			}
			
			
		}
			
	}
	
	
	
}