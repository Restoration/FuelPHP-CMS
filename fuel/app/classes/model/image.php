<?php
class Model_Image extends Model_Crud{

	/**
	 * Get uploaded image data
	 *
	 * @access  public
	 * @return  Response
	 */
    public static function get_result()
    {
		$table_name = 'trn_file';
		$count_sql = \DB::select()->where('dltflg','=',0)->from($table_name);
		$count_result = $count_sql->execute();
		$count = count($count_result);
		$config = array(
			'total_items'    => $count,
			'per_page'       => 12,
			'uri_segment'    => 'page',
		);
		$pagination = \Pagination::forge('pagination', $config);
		$query = \DB::select()->from($table_name);
		$query->limit($pagination->per_page)->offset($pagination->offset);
		$query->order_by('registerdate','DESC');
		$query->having('dltflg','=',0);
		return array( $query->execute()->as_array(),$count);
	}

	/**
	 * Insert file data
	 *
	 * @access  public
	 * @param   file data
	 * @param   path to save
	 * @return  Response
	 */
    public static function add_action($file_data,$save_path)
    {
		$table_name = 'trn_file';
		$url =  (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"].Uri::base(false);
		for($i=0;$i<count($file_data);$i++){
			$data[$i]['file_name'] = $file_data[$i]['name'];
			$data[$i]['file_saved_as'] = $file_data[$i]['saved_as'];
			$data[$i]['file_saved_to'] = $file_data[$i]['saved_to'];
			$data[$i]['file_saved_abs_to'] = $url.'public/assets/img'.$save_path;
			$data[$i]['file_extension'] = $file_data[$i]['extension'];
			$data[$i]['file_path'] = $file_data[$i]['file'];
			$data[$i]['registerdate'] = date("Y-m-d H:i:s");
			$query = \DB::insert($table_name)->set($data[$i]);
			$query->execute();
		}
		if(empty($_POST['inputflg'])){
			return $data;
		}
    }

	/**
	 * Delete upload file
	 *
	 * @access  public
	 * @param   file id
	 * @return  Response
	 */
    public static function delete_action($file_id)
    {
		$table_name = 'trn_file';
		\DB::delete($table_name)->where('file_id','=',$file_id)->execute();
    }


	/**
	* Show pagenation
	*
	* @access  public
	* @param   limit
	* @param   page navi
	* @return string|html
	*/
	public static function image_pagenation($limit = "6",$page_navi="5"){
	     if(isset($_GET['page'])){
	          if(preg_match('/^[1-9][0-9]*$/',$_GET['page'])){
	            $page=(int)$_GET['page'];
	          }else{
	            $page=1;
	          }
	     } else {
	          $page=1;
	     }
		$table_name = 'trn_file';
		$count_sql = \DB::select()->where('dltflg','=',0)->from($table_name);
		$count_result = $count_sql->execute();
		$count = count($count_result);
	    $set= $limit * ($page-1);
	    $current_page = $page;
	    $total_rec = $count;
	    $total_page = ceil($total_rec / $limit);
	    $show_nav = $page_navi;
	    $path = Uri::base(false).'/image/image_iframe?page=';
	    if ($total_page < $show_nav) {
	        $show_nav = $total_page;
	    }
	    $show_navh = floor($show_nav / 2);
	    $loop_start = $current_page - $show_navh;
	    $loop_end = $current_page + $show_navh;
	    if ($loop_start <= 0) {
	        $loop_start  = 1;
	        $loop_end = $show_nav;
	    }
	    if ($loop_end > $total_page) {
	        $loop_start  = $total_page - $show_nav +1;
	        $loop_end =  $total_page;
	    }

	    if ($total_page <= 1 || $total_page < $current_page ) {
	          return;
	    }
	    $html = '';
	    $html .= '<div class="pagination">';
	    if ( $current_page > 1){
		    $html .= '<span class="prev"><a href="'. $path . ($current_page-1).'" data-page="'.($current_page-1).'">&laquo;</a></span>';
		} else {
			$html .= '<span class="prev"><a href="#">&laquo;</a></span>';
		}
	    for ($i=$loop_start; $i<=$loop_end; $i++) {
	        if ($i > 0 && $total_page >= $i) {
	            if($i == $current_page) $html .= '<span class="active">';
	            else $html .= '<span>';
	            $html .= '<a href="'. $path . $i.'" data-page="'.$i.'">'.$i.'</a>';
	            $html .= '</span>';
	        }
	    }
	    if ( $current_page < $total_page){
		    $html .= '<span class="next"><a href="'. $path . ($current_page+1).'" data-page="'.($current_page+1).'">&raquo;</a></span>';
		} else {
			$html .= '<span class="next"><a href="#">&raquo;</a></span>';
		}
	    $html .= '</div>';
	    return $html;
	}
}