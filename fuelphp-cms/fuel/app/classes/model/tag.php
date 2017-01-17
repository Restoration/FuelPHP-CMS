<?php
class Model_Tag extends Model_Crud{

	//結果取得
    public static function get_result()
    {
		$table_name = 'mtr_tag';

		$count_sql = DB::select()->from($table_name);
		$count_sql->where('dltflg','=',0);
		$count_result = $count_sql->execute();
		$count = count($count_result);

		//ページネーション
		$config = array(
			//'pagination_url' => '',
			'total_items'    => $count,
			'per_page'       => 10,
			'uri_segment'    => 'page',
		);
		//ページャーインスタンス作成
		$pagination = Pagination::forge('pagination', $config);
		$query = DB::select()->from($table_name);
		$query->where('dltflg','=',0);
		$query->limit($pagination->per_page)->offset($pagination->offset);
		$query->order_by('registerdate','DESC');
		$result = $query->execute()->as_array();
		return $result;
	}


	//タグのみ取得
    public static function get_tag()
    {
		$table_name = 'mtr_tag';
		$query = DB::select()->from($table_name);
		$query->where('dltflg','=',0);
		$query->order_by('registerdate','DESC');
		$result = $query->execute()->as_array();
		return $result;
	}

	//idのリクエストがあれば結果を返す
	public static function preview($id){
		$table_name = 'mtr_tag';
		$query = DB::select()->from($table_name);
		$query->where("tag_id",'=',$id);
		$query->and_where("dltflg",'=',0);
		return $query->execute()->as_array();
	}


	//追加
    public static function insert_action()
    {
		$table_name = 'mtr_tag';
		unset($_POST['add']['save']);

		if(empty($_POST['add']['tag_slug'])){
			$tag_name = preg_replace('/(\s|　)/','-',$_POST['add']['tag_name']);
			$tag_name = mb_strtolower($tag_name);
			$_POST['add']['tag_slug'] = $tag_name;
		}
		$model_tag = new Model_Tag();
		$count = $model_tag->validate_slug($_POST['add']['tag_slug']);
		if($count >= 1){

			$suffix = '-'.(intval($count)+1);
			$_POST['add']['tag_slug'] = $_POST['add']['tag_slug'].$suffix;
			$re_count = $model_tag->validate_slug($_POST['add']['tag_slug']);
			if($re_count >= 1){
				$i = 0;
				do {
					$num = substr($_POST['add']['tag_slug'],-1);
					$re_num = intval($num) + 1;
					$_POST['add']['tag_slug'] = substr($_POST['add']['tag_slug'], 0, -1);
					$_POST['add']['tag_slug'] = $_POST['add']['tag_slug'].$re_num;
					$re_count = $model_tag->validate_slug($_POST['add']['tag_slug']);
				} while ($i < $re_count);
			}
		}
		$_POST['add']['registerdate'] = date("Y-m-d H:i:s");
		$data = $_POST['add'];
		$query = DB::insert($table_name)->set($data);
		return $query->execute();
    }

	//更新,削除
    public static function edit_action()
    {
		$table_name = 'mtr_tag';
		if(!empty($_POST['edit']['save'])){
			unset($_POST['edit']['save']);
			if(empty($_POST['edit']['tag_slug'])){
				$tag_name = preg_replace('/(\s|　)/','-',$_POST['edit']['tag_name']);
				$tag_name = mb_strtolower($tag_name);
				$_POST['edit']['tag_slug'] = $tag_name;
			}
			$model_tag = new Model_Tag();

			$cnt = $model_tag->validate_slug($_POST['edit']['tag_slug']);
			if($cnt == 1){
				$count = $model_tag->validate_update_slug($_POST['edit']['tag_id'],$_POST['edit']['tag_slug']);
				if($count >= 1){
					$suffix = '-'.(intval($count)+1);
					$_POST['edit']['tag_slug'] = $_POST['edit']['tag_slug'].$suffix;
					$re_count = $model_tag->validate_slug($_POST['edit']['tag_slug']);
					if($re_count >= 1){
						$i = 0;
						do {
							$num = substr($_POST['edit']['tag_slug'],-1);
							$re_num = intval($num) + 1;
							$_POST['edit']['tag_slug'] = substr($_POST['edit']['tag_slug'], 0, -1);
							$_POST['edit']['tag_slug'] = $_POST['edit']['tag_slug'].$re_num;
							$re_count = $model_tag->validate_slug($_POST['edit']['tag_slug']);
						} while ($i < $re_count);
					}
				}
			}




			$data = $_POST['edit'];
			$data['modified'] = date("Y-m-d H:i:s");
			$query = DB::update($table_name)->where('tag_id','=',$data['tag_id'])->set($data);
			return $query->execute();
		} else {
			$data = $_POST['edit'];
			$data['modified'] = date("Y-m-d H:i:s");
			$data['dltflg'] = 1;
			$query = DB::update($table_name)->where('tag_id','=',$data['tag_id'])->set($data);
			return $query->execute();
		}
    }

	//検索
    public static function tag_search_action($search_value)
    {
		$table_name = 'mtr_tag';
		$post = $search_value;

		$count_sql = DB::select()->from($table_name);
		if(strlen($post) > 0){
		    $post = str_replace("　", " ",$post);//全角を半角に変換
		    $array = explode(" ",$post);
		    for($i =0; $i <count($array); $i++){
		        $count_sql->and_where('tag_name', 'LIKE',"%$array[$i]%");
		    }
		}
		$count_sql->and_where('dltflg','=',0);
		$count_result = $count_sql->execute()->as_array();
		$count = count($count_result);

		//ページネーション
		$config = array(
			//'pagination_url' => '',
			'total_items'    => $count,
			'per_page'       => 10,
			'uri_segment'    => 'page',
		);
		//ページャーインスタンス作成
		$pagination = Pagination::forge('pagination', $config);
		$query = DB::select()->from($table_name);
		if(strlen($post) > 0){
		    $post = str_replace("　", " ",$post);//全角を半角に変換
		    $array = explode(" ",$post);
		    for($i =0; $i <count($array); $i++){
		        $query->and_where('tag_name', 'LIKE',"%$array[$i]%");
		    }
		}
		$query->and_where('dltflg','=',0);
		$query->limit($pagination->per_page)->offset($pagination->offset);
		$query->order_by('registerdate','DESC');
		$result['tag'] = $query->execute()->as_array();
		$result['pagination'] = \Pagination::instance('pagination')->render();
		return $result;
    }

	private static function validate_slug($slug)
	{
		$table_name = 'mtr_tag';
		$count_sql = DB::select('tag_id')->from($table_name);
		$count_sql->where('tag_slug','=',$slug);
		$count_sql->and_where('dltflg','=',0);
		$count_result = $count_sql->execute()->as_array();
		$count = count($count_result);
		return $count;
	}

	private static function validate_update_slug($id,$slug)
	{
		$table_name = 'mtr_tag';
		$count_sql = DB::select('tag_id')->from($table_name);
		$count_sql->where('tag_slug','=',$slug);
		$count_sql->and_where('tag_id','=',$id);
		$count_sql->and_where('dltflg','=',0);
		$count_result = $count_sql->execute()->as_array();
		$count = count($count_result);
		if($count == 1){
			$count = 0;
		} else {
			$count = 1;
		}
		return $count;
	}



}