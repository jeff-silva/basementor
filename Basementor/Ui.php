<?php

namespace Basementor;

class Ui
{
	static function pagination($query, $base=null) {
		$base = rtrim($base? $base: site_url(), '/');

		$data = new \stdClass;
		$data->page = get_query_var('paged');
		$data->page = $data->page? $data->page: 1;
		$data->results = $query->found_posts;
		$data->per_page = $query->post_count;
		$data->pages = $query->max_num_pages;
		$data->range = 5;
		$data->page_start = $data->page - $data->range;
		$data->page_start = max($data->page_start, 1);
		$data->page_start = min($data->page_start, $data->pages);
		$data->page_final = $data->page_start + ($data->range * 2);
		$data->page_final = min($data->page_final, $data->pages);
		if ($data->pages<=1) return;

		?>
		<nav aria-label="Page navigation example">
			<ul class="pagination">
				<li class="page-item">
					<a class="page-link <?php echo $data->page==1? 'bg-primary text-light': null; ?>" href="<?php echo "{$base}/page/1/". (empty($_GET)? null: ('?'.http_build_query($_GET))); ?>">
						<i class="fa fa-fw fa-chevron-left"></i>
					</a>
				</li>
				<?php for($p=$data->page_start; $p<=$data->page_final; $p++): ?>
				<li class="page-item">
					<a class="page-link <?php echo $data->page==$p? 'bg-primary text-light': null; ?>" href="<?php echo "{$base}/page/{$p}/". (empty($_GET)? null: ('?'.http_build_query($_GET))); ?>">
						<?php echo $p; ?>
					</a>
				</li>
				<?php endfor; ?>
				<li class="page-item">
					<a class="page-link <?php echo $data->page==$data->pages? 'bg-primary text-light': null; ?>" href="<?php echo "{$base}/page/{$data->pages}/". (empty($_GET)? null: ('?'.http_build_query($_GET))); ?>">
						<i class="fa fa-fw fa-chevron-right"></i>
					</a>
				</li>
			</ul>
		</nav>
		<?php
	}
}