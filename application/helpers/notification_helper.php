<?php
    function sendnotification($member_id,$project_id,$question_id,$is_removed = FALSE)
    {
        $ci = &get_instance();

        if(!empty($project_id)) {
            $project = getprojectdata($project_id);
            $member_info = getmemberdata($project->row()->member_id);
            $prof_img = !empty($member_info->row()->profile_image) ? $member_info->row()->profile_image:'https://cdn.vnoc.com/icons/default-80x80.jpg';
            $link = '/projects/'.$project->row()->id.'/'.$project->row()->slug;
            $name = !empty($member_info->row()->firstname) && !empty($member_info->row()->lastname) ? ucwords($member_info->row()->firstname.' '.$member_info->row()->lastname) : $member_info->row()->username;
            
            if($is_removed == TRUE) {
                $msg = $name.' removed you in project '.$project->row()->title.'.';
            } else {
                $msg = $name.' added you to project '.$project->row()->title.'.';
            }
        } elseif(!empty($question_id)) {
            $question = getquestiondata($question_id);
            $member_info = getmemberdata($member_id);
            $prof_img = !empty($member_info->row()->profile_image) ? $member_info->row()->profile_image:'https://cdn.vnoc.com/icons/default-80x80.jpg';
            $link = '/questions/'.$question->row()->id.'/'.$question->row()->slug;
            $name = !empty($member_info->row()->firstname) && !empty($member_info->row()->lastname) ? ucwords($member_info->row()->firstname.' '.$member_info->row()->lastname) : $member_info->row()->username;

            $member_id = $question->row()->member_id;
            $msg = $name.' replied to your question.';
        }
        //project
        //catacte added you to project ----> to project owner


        //questions
        // joseph replied to your questions ----> to joseph

        $noti_msg = [
            'prof_img'=>$prof_img,
            'link'=>$link,
            'msg'=>$msg
        ];

        $message = $ci->load->view('notifications/noti_msg',$noti_msg,TRUE);
        $noti_id = $ci->membernotificationsdata->update(0,['member_id'=>$member_id,'message'=>$message,'is_read'=>'0']);

        return $noti_id;
    }

    function getmemberdata($member_id) {
        $ci = &get_instance();
        $sql = "SELECT * FROM `members` WHERE `id` = $member_id";

        $project = $ci->db->query($sql);
        return $project;
    }

    function getprojectdata($project_id) {
        $ci = &get_instance();
        $sql = "SELECT * FROM `member_projects` WHERE `id` = $project_id";

        $project = $ci->db->query($sql);
        return $project;
    }

    function getquestiondata($question_id) {
        $ci = &get_instance();
        $sql = "SELECT * FROM `member_questions` WHERE `id` = $question_id";

        $project = $ci->db->query($sql);
        return $project;
    }
?>