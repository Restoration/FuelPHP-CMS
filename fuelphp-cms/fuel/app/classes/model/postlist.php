<?php
class Model_Postlist extends Model_Crud{

	//結果取得
    public static function get_result()
    {
		$table_name = 'tbl_post';

		$count_sql = DB::select('post_id','post_title','post_message','registerdate','dltflg')->from($table_name);
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

	//更新,削除
    public static function edit_action()
    {
		$table_name = 'tbl_post';

		if(!empty($_POST['edit']['save'])){
			try {
				DB::start_transaction();


				unset($_POST['edit']['save']);
				$category = isset($_POST['edit']['category']) ? $_POST['edit']['category'] : '';
				$tag = isset($_POST['edit']['tag']) ? $_POST['edit']['tag'] : '';
				unset($_POST['edit']['category']);
				unset($_POST['edit']['tag']);

				$data = $_POST['edit'];
				if($_POST['edit']['post_title'] == ''){
					$data['post_title'] = 'タイトル無し';
				}
				$data['modified'] = date("Y-m-d H:i:s");
				$query = DB::update($table_name)->where('post_id','=',$data['post_id'])->set($data);
				$query->execute();
				/*Category
				--------------------*/
				$category_table = 'trn_category';
				if(!empty($category)){
					\DB::delete($category_table)->where('post_id','=',$data['post_id'])->execute();
					$category_data['post_id'] = $data['post_id'];
					for($i=0; $i< count($category); $i++){
						$category_data['category_id'] = $category[$i];
						$category_query = DB::insert($category_table)->set($category_data);
						$category_query->execute();
					}
				}
				/*Tag
				--------------------*/
				$tag_table = 'trn_tag';
				if(!empty($tag)){
					\DB::delete($tag_table)->where('post_id','=',$data['post_id'])->execute();
					$tag_data['post_id'] = $data['post_id'];
					for($i=0; $i< count($tag); $i++){
						$tag_data['tag_id'] = $tag[$i];
						$tag_query = DB::insert($tag_table)->set($tag_data);
						$tag_query->execute();
					}
				}
				DB::commit_transaction();
			}catch(\Database_exception $e){
				DB::rollback_transaction();
			}
		} else {
			$data = $_POST['edit'];
			$data['modified'] = date("Y-m-d H:i:s");
			$data['dltflg'] = 1;
			$query = DB::update($table_name)->where('post_id','=',$data['post_id'])->set($data);
			return $query->execute();
		}
/*
		echo DB::last_query();
		exit();
*/
    }

	//更新,削除
    public static function post_search_action($search_value)
    {
		$table_name = 'tbl_post';
		$post = $search_value;

		$count_sql = DB::select('post_id','post_title','post_message','registerdate','dltflg')->from($table_name);
		if(strlen($post) > 0){
		    $post = str_replace("　", " ",$post);//全角を半角に変換
		    $array = explode(" ",$post);
		    for($i =0; $i <count($array); $i++){
		        $count_sql->and_where('post_title', 'LIKE',"%$array[$i]%");
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
		        $query->and_where('post_title', 'LIKE',"%$array[$i]%");
		    }
		}
		$query->and_where('dltflg','=',0);
		$query->limit($pagination->per_page)->offset($pagination->offset);
		$query->order_by('registerdate','DESC');
		$result['post'] = $query->execute()->as_array();
		$result['pagination'] = \Pagination::instance('pagination')->render();
		return $result;
    }
}