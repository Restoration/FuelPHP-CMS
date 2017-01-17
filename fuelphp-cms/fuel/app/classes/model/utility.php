<?php
class Model_Utility extends Model_Crud{
	//エスケープ処理
	public static function h($val){
		if(is_array($val)){
			return array_map('h',$val);
		} else {
			return htmlspecialchars($val,ENT_QUOTES,'UTF-8');
		}
	}
	//idのリクエストがあれば結果を返す
	public static function preview($id){
		$table_name = 'tbl_post';
		$mtr_tag_table = 'mtr_tag';
		$trn_tag_table = 'trn_tag';
		$mtr_category_table = 'mtr_category';
		$trn_category_table = 'trn_category';

		$query = DB::select(
			"$table_name.post_id",
			"$table_name.post_title",
			DB::expr("GROUP_CONCAT( DISTINCT IFNULL(mtr_category.category_name,'') , '' ) AS category_name"),
			DB::expr("GROUP_CONCAT( DISTINCT IFNULL(mtr_category.category_id,'') , '' ) AS category_id"),
			DB::expr("GROUP_CONCAT( DISTINCT IFNULL(mtr_tag.tag_name,'') , '' ) AS tag_name"),
			DB::expr("GROUP_CONCAT( DISTINCT IFNULL(mtr_tag.tag_id,'') , '' ) AS tag_id"),
			"$table_name.post_message",
			"$table_name.registerdate"
			)->from($table_name);
		$query->join($trn_tag_table, 'LEFT')->on("$trn_tag_table.post_id", '=', "$table_name.post_id");
		$query->join($mtr_tag_table, 'LEFT')->on("$mtr_tag_table.tag_id", '=', "$trn_tag_table.tag_id");
		$query->join($trn_category_table, 'LEFT')->on("$trn_category_table.post_id", '=', "$table_name.post_id");
		$query->join($mtr_category_table, 'LEFT')->on("$mtr_category_table.category_id", '=', "$trn_category_table.category_id");
		$query->where("$table_name.post_id",'=',$id);
		$query->and_where("$table_name.dltflg",'=',0);
		$query->order_by("$table_name.post_id",'desc');
		$query->group_by("$table_name.post_id");
		return $query->execute()->as_array();
	}
	//idのリクエストがあればユーザーを返す
	public static function preview_user($id){
		$table_name = 'tbl_user';
		$query = DB::select()->where('id','=',$id)->from($table_name);
		return $query->execute()->as_array();
	}


	//その日付のディレクトリ作成
	public function make_date_dir($dir){
		if(!is_dir($dir)){
			mkdir($dir);
		}
		$date_array = array(date('Y'),date('m'));
		for($i=0; $i < count($date_array); $i++){
			$this->make_dir($dir,$date_array[$i]);
			$dir .= '/'.$date_array[$i];
		}
		return true;
	}

	function make_dir($dir,$name){
		if(substr($dir,-1) !== "/"){
			$dir .= "/";
		}
		if(is_dir($dir.$name)){
			return false;
		}
		$dir .= $name;
		if(mkdir($dir)){
			return true;
		}
	}

}