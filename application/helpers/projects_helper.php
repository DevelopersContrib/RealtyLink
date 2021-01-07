<?php
    function get_project_milestones($projectId) {
        $ci = &get_instance();
        $sql = "SELECT milestone.project_id, milestone.id milestone_id, milestone.title milestone_title, GREATEST(milestone.date_created, IFNULL(pt.date_update, '2000-01-01 00:00:01')) latest,
                DATE_FORMAT(milestone.date_created, '%b. %e, %Y %l:%i %p') AS milestone_date_created, pt.date_update, member_projects.slug,
                createdby.id createdby_id, createdby.firstname, createdby.lastname, createdby.username, createdby.`profile_image`
                FROM `project_tasks` pt 
                INNER JOIN `project_tasks` milestone ON pt.parent_id = milestone.id
                INNER JOIN members createdby ON milestone.created_by = createdby.id
                INNER JOIN `member_projects` ON milestone.project_id = member_projects.id
                WHERE milestone.project_id = $projectId
                GROUP BY milestone_id
                ORDER BY milestone.date_created DESC;";

        $milestones = $ci->db->query($sql);
        return $milestones;
    }

    function get_milestone_tasks($projectId, $taskId) {
        $ci = &get_instance();
        
        $sql = "SELECT `project_tasks`.*,DATE_FORMAT(`date_created`, '%b. %e, %Y %l:%i %p') AS date_created, members.firstname,members.lastname,members.username,members.profile_image
                FROM `project_tasks`
                LEFT JOIN members ON `project_tasks`.assigned_to = members.id
                WHERE `project_id` = $projectId AND `parent_id` = $taskId
                ORDER BY id DESC;";

        $tasks = $ci->db->query($sql);
        return $tasks;
    }

    function is_latest_task_in_milestone($projectId) {
        $ci = &get_instance();
        
        $sql = "SELECT *
                FROM project_tasks
                WHERE project_id = $projectId
                ORDER BY id DESC
                LIMIT 1;";

        $result = $ci->db->query($sql);

        return $result->row()->parent_id != 0 ? TRUE:FALSE;
    }

    function search_in_project($projectId,$key,$select,$limit=NULL,$offset=NULL) {
        $ci = &get_instance();

        $condition = "WHERE pt.project_id = $projectId";
        $limitCondition = '';
        
        if($select == 'tasks') {
            $condition .= " AND pt.title LIKE '%$key%' AND pt.is_milestone = 0";
        } elseif($select == 'milestones') {
            $condition .= " AND pt.title LIKE '%$key%' AND pt.is_milestone = 1";
        } else {
            $condition .= " AND (mat.username LIKE '%$key%' OR mat.firstname LIKE '%$key%' OR mat.lastname LIKE '%$key%' OR mcb.username LIKE '%$key%' OR mcb.firstname LIKE '%$key%' OR mcb.lastname LIKE '%$key%')";
        }

        if($limit !== NULL && $offset !== NULL) {
            $limitCondition = "LIMIT $limit OFFSET $offset;";
        }
        
        $sql = "SELECT pt.id, pt.title, DATE_FORMAT(pt.date_created, '%b. %e, %Y %l:%i %p') date_created, pt.status,
                mat.username mat_username, mat.firstname mat_firstname, mat.lastname mat_lastname, mat.profile_image mat_profile_image,
                mcb.username mcb_username, mcb.firstname mcb_firstname, mcb.lastname mcb_lastname, mcb.profile_image mcb_profile_image
                FROM project_tasks pt
                INNER JOIN members mat ON (mat.id = pt.assigned_to)
                INNER JOIN members mcb ON (mcb.id = pt.created_by)
                $condition
                ORDER by pt.id DESC
                $limitCondition";

        $totalItems = total_search_items($condition);

        $result = $ci->db->query($sql);

        return ['result'=>$result,'totalItems'=>$totalItems];
    }

    function total_search_items($condition) {
        $ci = &get_instance();

        $sql = "SELECT COUNT(*) AS totalItems
                FROM project_tasks pt
                INNER JOIN members mat ON (mat.id = pt.assigned_to)
                INNER JOIN members mcb ON (mcb.id = pt.created_by)
                $condition";
        $result = $ci->db->query($sql);

        return $result->row()->totalItems;
    }
?>