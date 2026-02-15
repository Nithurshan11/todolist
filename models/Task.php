<?php
class Task {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($uid, $task, $date, $priority) {
        $stmt = $this->conn->prepare("INSERT INTO tasks (user_id,task_name,due_date,priority) VALUES (?,?,?,?)");
        $stmt->bind_param("isss", $uid, $task, $date, $priority);
        return $stmt->execute();
    }

    public function getAll($uid) {
        $stmt = $this->conn->prepare("SELECT * FROM tasks WHERE user_id=? ORDER BY due_date ASC");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE tasks SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    // ✅ New method to mark task as complete
    public function complete($id) {
        return $this->updateStatus($id, "Completed");
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM tasks WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>