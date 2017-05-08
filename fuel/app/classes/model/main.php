<?php
class Model_Main extends Model_Crud{

	/**
	 * Get post data
	 *
	 * @access  public
	 * @return  Response
	 */
    public static function get_result()
    {
		$table_name = 'trn_post';
		// Pagination
		$count_sql = \DB::select()->where('dltflg','=',0)->from($table_name);
		$count_result = $count_sql->execute();
		$count = count($count_result);
		$config = array(
			'total_items'    => $count,
			'per_page'       => 10,
			'uri_segment'    => 'page',
		);
		$pagination = \Pagination::forge('pagination', $config);
		$query = \DB::select('post_id','post_title','post_message','post_category','registerdate','dltflg')->from($table_name);
		$query->limit($pagination->per_page)->offset($pagination->offset);
		$query->having('dltflg','=',0);
		return $query->execute()->as_array();
	}

	/**
	 * Post insert action
	 *
	 * @access  public
	 * @return  Response
	 */
    public static function insert_action()
    {
		try {
			\DB::start_transaction();
			$table_name = 'trn_post';
			unset($_POST['add']['save']);
			if($_POST['add']['registerdate'] == ''){
				$_POST['add']['registerdate'] = date("Y-m-d H:i:s");
			}
			$category = isset($_POST['add']['category']) ? $_POST['add']['category'] : '';
			$tag = isset($_POST['add']['tag']) ? $_POST['add']['tag'] : '';
			unset($_POST['add']['category']);
			unset($_POST['add']['tag']);

			if($_POST['add']['post_title'] == ''){
				$data['post_title'] = 'No Title';
			}
			$data = $_POST['add'];
			$query = \DB::insert($table_name)->set($data);
			$query_array = $query->execute();
			//Get last inserted id
			$last_id = $query_array[0];
			/*Category
			--------------------*/
			$category_table = 'trn_category';
			if(!empty($category)){
				$category_data['post_id'] = $last_id;
				for($i=0; $i< count($category); $i++){
					$category_data['category_id'] = $category[$i];
					$category_query = \DB::insert($category_table)->set($category_data);
					$category_query->execute();
				}
			}
			/*Tag
			--------------------*/
			$tag_table = 'trn_tag';
			if(!empty($tag)){
				$tag_data['post_id'] = $last_id;
				for($i=0; $i< count($tag); $i++){
					$tag_data['tag_id'] = $tag[$i];
					$tag_query = \DB::insert($tag_table)->set($tag_data);
					$tag_query->execute();
				}
			}
			\DB::commit_transaction();
		}catch(\Database_exception $e){
			\DB::rollback_transaction();
		}
    }
	/**
	 * Get post data from id
	 *
	 * @access  public
	 * @return  Response
	 */
	public static function preview($id){
		$table_name = 'trn_post';
		$query = \DB::select('post_id','post_name','post_title','post_message','registerdate','post_key')->where('post_id','=',$id)->from($table_name);
		return $query->execute()->as_array();
	}
}