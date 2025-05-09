<?php

namespace App\Services;

use App\Models\Course_class;
use App\Models\ScheduleSlot;
use App\Services\Validators\ClassroomConflictValidator;
use App\Services\Validators\InstructorConflictValidator;

class ScheduleService {

    protected array $courseValidators = [];
    protected array $classroomValidators = [];

    public function __construct(){
        $this->courseValidators = [
            new InstructorConflictValidator(),
        ];
        $this->classroomValidators = [
            new ClassroomConflictValidator(),
        ];
    }

    public function addCourseToSchedule($scheduleId, $classId, $day, $startTime, $force = false)
    {

        $course = Course_class::find($classId);
        if(!$course){
            return ['success' => false];
        }

        if ($course->UnscheduledHours < 1) {
            return ['success' => false,'status' => 'Ders zaten planlanmış'];
        }
        $endTime = date('H:i', strtotime($startTime . ' +45 minute'));
        $conflicts = $this->detectConflicts($course->instructorId, $day, $startTime, $endTime,$this->courseValidators);

        if (!empty($conflicts) && !$force) {
            return ['has_conflicts' => true, 'conflicts' => $conflicts];
        }
        $slot = ScheduleSlot::create([
            'schedule_id' => $scheduleId,
            'class_id' => $classId,
            'classroom_id' => null,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'day' => $day,
        ]);
        return ['success' => true, 'slot' => $slot];
    }



    public function addClassroomToSlot($classroomId,$scheduleId,$day,$startTime, $force = false)
    {
        $endTime = date('H:i', strtotime($startTime . ' +45 minute'));

        $conflicts = $this->detectConflicts($classroomId, $day, $startTime, $endTime,$this->classroomValidators);
        if (!empty($conflicts) && !$force) {
            return ['has_conflicts' => true, 'conflicts' => $conflicts];
        }else{

             $slot = ScheduleSlot::query()->where('schedule_id',$scheduleId)
                 ->where('day',$day)
                 ->where('start_time',$startTime)->first();
            if($slot){
                $slot->classroom_id = $classroomId;
                $slot->save();
                return ['success' => true, 'slot' => $slot];

            }
        }
        return ['success' => false, 'slot' => $slot];
    }
    private function detectConflicts($id, $day, $startTime, $endTime,$validators): array
    {
        $conflicts = [];
        foreach ($validators as $validator) {
            $result = $validator->validate($id, $day, $startTime, $endTime);
            if ($result !== true) {
                $conflicts[$validator->getName()] = $result;
            }
        }
        return $conflicts;
    }


}
