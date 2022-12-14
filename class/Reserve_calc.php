<?php

require_once '/Applications/MAMP/htdocs/Deliverables4/class/Database_calc.php';

class Reserve
{
    /**
     * インターンシップ情報に予約ができるか判定する
     */
    public function intern_information_reserve_check($post_id, $student_id)
    {
        $db_calc = new Database();

        // 投稿にいいねできるか確認する。
        $sql = 'SELECT * FROM intern_information_reserve_tbl WHERE reserve_post_id = ? AND student_id = ?';

        $argument = [];
        $argument[] = strval($post_id);
        $argument[] = strval($student_id);

        $result = $db_calc->data_select_argument($sql, $argument);

        return $result;
    }

    /**
     * インターンシップ情報各投稿の予約数を取得する
     */
    public function intern_information_reserve_count($post_id)
    {
        $db_calc = new Database();

        $sql = 'SELECT * FROM intern_information_reserve_tbl WHERE reserve_post_id = ?';

        $argument[] = strval($post_id);

        $result = $db_calc->data_select_count($sql, $argument);

        return $result;
    }

    /**
     * インターンシップ情報に予約する
     */
    public function intern_information_reserve($post_id, $student_id)
    {
        $db_calc = new Database();

        $sql = "INSERT INTO `intern_information_reserve_tbl` (`reserve_post_id`, `student_id`) VALUES (?, ?)";

        $argument = [];
        $argument[] = strval($post_id);
        $argument[] = strval($student_id);

        $result = $db_calc->data_various_kinds($sql, $argument);

        return $result;
    }

    /**
     * インターンシップ情報の予約を解除する
     */
    public function intern_information_reserve_delete($post_id, $student_id)
    {
        $db_calc = new Database();

        $sql = "DELETE FROM `intern_information_reserve_tbl` WHERE reserve_post_id = ? AND student_id = ?";

        $argument = [];
        $argument[] = strval($post_id);
        $argument[] = strval($student_id);

        $result = $db_calc->data_various_kinds($sql, $argument);

        return $result;
    }

    /**
     * インターンシップ情報投稿に予約した学生の情報を取得する
     */
    public function intern_information_reserve_data($post_id)
    {
        $db_calc = new Database();

        $sql = "SELECT * FROM `intern_information_reserve_tbl` INNER JOIN `student_mst` ON intern_information_reserve_tbl.student_id = student_mst.student_id AND intern_information_reserve_tbl.reserve_post_id = ? ORDER BY intern_information_reserve_tbl.reserve_id DESC";

        $argument = [];
        $argument[] = strval($post_id);

        $result = $db_calc->data_select_argument($sql, $argument);

        return $result;
    }

    /**
     * 会社説明会情報に予約ができるか判定する
     */
    public function briefing_information_reserve_check($post_id, $student_id)
    {
        $db_calc = new Database();

        // 投稿にいいねできるか確認する。
        $sql = 'SELECT * FROM briefing_information_reserve_tbl WHERE reserve_post_id = ? AND student_id = ?';

        $argument = [];
        $argument[] = strval($post_id);
        $argument[] = strval($student_id);

        $result = $db_calc->data_select_argument($sql, $argument);

        return $result;
    }

    /**
     * 会社説明会情報各投稿の予約数を取得する
     */
    public function briefing_information_reserve_count($post_id)
    {
        $db_calc = new Database();

        $sql = 'SELECT * FROM briefing_information_reserve_tbl WHERE reserve_post_id = ?';

        $argument[] = strval($post_id);

        $result = $db_calc->data_select_count($sql, $argument);

        return $result;
    }

    /**
     * インターンシップ情報に予約する
     */
    public function briefing_information_reserve($post_id, $student_id)
    {
        $db_calc = new Database();

        $sql = "INSERT INTO `briefing_information_reserve_tbl` (`reserve_post_id`, `student_id`) VALUES (?, ?)";

        $argument = [];
        $argument[] = strval($post_id);
        $argument[] = strval($student_id);

        $result = $db_calc->data_various_kinds($sql, $argument);

        return $result;
    }

    /**
     * 会社説明会情報の予約を解除する
     */
    public function briefing_information_reserve_delete($post_id, $student_id)
    {
        $db_calc = new Database();

        $sql = "DELETE FROM `briefing_information_reserve_tbl` WHERE reserve_post_id = ? AND student_id = ?";

        $argument = [];
        $argument[] = strval($post_id);
        $argument[] = strval($student_id);

        $result = $db_calc->data_various_kinds($sql, $argument);

        return $result;
    }

    /**
     * インターンシップ情報投稿に予約した学生の情報を取得する
     */
    public function briefing_information_reserve_data($post_id)
    {
        $db_calc = new Database();

        $sql = "SELECT * FROM `briefing_information_reserve_tbl` INNER JOIN `student_mst` ON briefing_information_reserve_tbl.student_id = student_mst.student_id AND briefing_information_reserve_tbl.reserve_post_id = ? ORDER BY briefing_information_reserve_tbl.reserve_id DESC";

        $argument = [];
        $argument[] = strval($post_id);

        $result = $db_calc->data_select_argument($sql, $argument);

        return $result;
    }
}
