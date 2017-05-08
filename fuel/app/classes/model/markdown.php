<?php
class Model_Markdown extends Model_Crud{

	/**
	 * Get tag Data
	 *
	 * @access  public
	 * @return  Response
	 */
    public static function get_result()
    {
		$table_name = 'mtr_md';
		$count_sql = \DB::select()->from($table_name);
		$count_result = $count_sql->execute();
		$count = count($count_result);
		$config = array(
			'total_items'    => $count,
			'per_page'       => 10,
			'uri_segment'    => 'page',
		);
		$pagination = \Pagination::forge('pagination', $config);
		$query = \DB::select()->from($table_name);
		$query->limit($pagination->per_page)->offset($pagination->offset);
		$query->order_by('register','DESC');
		$result = $query->execute()->as_array();
		return $result;
	}

	/**
	 * Tag edit action
	 *
	 * @access  public
	 * @return  Response
	 */
    public function update_action($post)
    {
		$table_name = 'mtr_md';
		if(empty($post['delete'])){
			unset($post['save']);
				$post['register'] = date("Y-m-d H:i:s");
				$post['modified'] = date("Y-m-d H:i:s");
				if(empty($post['id'])){
					$query = \DB::insert($table_name)->set($post);
				} else {
					$query = \DB::update($table_name)->where('id','=',$post['id'])->set($post);
				}
				return $query->execute();
		} else {
			return \DB::delete($table_name)->where('id','=',$post['id'])->execute();
		}
    }


	/**
	 * Get single markdown data
	 *
	 * @access  public
	 * @return  Response
	 */
    public function preview($id)
    {
		$table_name = 'mtr_md';
		$query = \DB::select()->from($table_name)->where('id','=',$id);
		return $query->execute()->as_array();
    }
}