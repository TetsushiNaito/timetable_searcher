<?php
/*
Time: 時刻1つごとのオブジェクト
プロパティ：
dept_time: 出発時刻
route_name: 路線名
note: 備考
*/
class Time {
	public string $dept_time;
	public string $route_name;
	public string $note;
}

/*
TimeTable: 時刻表オブジェクト
プロパティ：
dept_poll: 出発地バス停名
dest_poll: 目的地バス停名
times: Timeオブジェクトの配列
メソッド：
getDeptTimeNow: 現在時刻から最も近い出発時刻を検索する
　引数：
　num: 表示する候補の数
*/

class TimeTable {
	public string $dept_poll;
	public string $dest_poll;
	public array $times;

	public function getDeptTimeNow( $num ) : array {
		$result = [];
		$now = new DateTime;
		$now_time = $now->format('H:i');
        // print "$now_time\n";
		for ( $i = 0; $i < count( $this->times ); $i++ ) {
			if ( strtotime( $this->times[$i]->dept_time ) > strtotime( $now_time ) ) { break; }
        }
        for ( $j = 0; $j < $num; $j++ ) {
            //print "hogehoge\n";
		    $result[] = $this->times[ $i + $j ];
		}
        return $result;
	}
}
