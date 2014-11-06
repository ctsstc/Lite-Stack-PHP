<?
class Page extends Model
{
	public static $_id_column = 'id';
	public static $_table = 'page';

	public static function getPages()
	{
		return
		ORM::for_table(self::$_table)
			->order_by_asc('order')
			->where('visible', 1);
	}
}
?>