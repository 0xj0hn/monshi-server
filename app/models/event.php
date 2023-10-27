<?php
class Event extends Model {
    public function addEvent() {
    }

    public function removeEvent($id) {
        $sql = "DELETE FROM events WHERE id = ?";
        $query = $this->query($sql, "i", $id);
        return $query ? true : false;
    }

    public function getEvents($managerId, $secretaryId) {
        $sql = "SELECT * FROM events WHERE manager_id = ? AND secretary_id = ?";
        $query = $this->query($sql, "ii", $managerId, $secretaryId);
        $events = [];
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $events[] = $row;
            }
            return $events;
        }
        return false;
    }

    public function getEvent($eventId, $managerId, $secretaryId) {
        $sql = "SELECT * FROM events WHERE id = ? AND manager_id = ? AND secretary_id = ?";
        $query = $this->query($sql, "iii", $eventId, $managerId, $secretaryId);
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                return $row;
            }
        }
        return false;
    }
}
