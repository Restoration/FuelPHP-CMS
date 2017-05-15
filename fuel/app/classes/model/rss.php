<?php
class Model_Rss extends Model_Crud{

	/**
	 * Get tag Data
	 *
	 * @access  public
	 * @return  Response
	 */
    public static function get_result()
    {
		$table_name = 'mtr_rss';
		$query = \DB::select()->from($table_name);
		$query->order_by('updated_at','DESC');
		$result = $query->execute()->as_array();
		return $result;
	}

	/**
	 * Category insert action
	 *
	 * @access  public
	 * @return  Response
	 */
    public static function insert_action($post)
    {
		$table_name = 'mtr_rss';
		unset($post['save']);
		try {
			\DB::start_transaction();
			\DB::delete($table_name)->execute();
			for($i=0; $i < count($post['rss']); $i++){
				$data['url'] = $post['rss'][$i];
				$data['created_at'] = date("Y-m-d H:i:s");
				$data['updated_at'] = date("Y-m-d H:i:s");
				$query = \DB::insert($table_name)->set($data);
				$query->execute();
			}
			\DB::commit_transaction();
		}catch(\Database_exception $e){
			\DB::rollback_transaction();
		}
    }

}