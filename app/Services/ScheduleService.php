<?php

namespace App\Services;

use App\Dto\ScheduleValidationData;
use App\Models\Course;
use App\Models\Course_class;
use App\Models\ScheduleSlot;
use App\Services\Validators\ClassroomConflictValidator;
use App\Services\Validators\CourseDuplicateValidator;
use App\Services\Validators\InstructorConflictValidator;

class ScheduleService {

    protected array $courseValidators = [];
    protected array $classroomValidators = [];

    public function __construct(){
        $this->courseValidators = [
            new CourseDuplicateValidator(),
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
        $validationData = new ScheduleValidationData(
            scheduleId: $scheduleId,
            classId: $course->id,
            classroomId: null,
            instructorId: $course->instructorId,
            day: $day,
            startTime: $startTime,
            endTime: $endTime,
            externalId: $course->external_id,
        );
        $conflicts = $this->detectConflicts($validationData, $this->courseValidators);
        if (!empty($conflicts) && !$force) {
            return ['has_conflicts' => true, 'conflicts' => $conflicts];
        }

        $slots = [];
        $slots []= ScheduleSlot::create([
            'schedule_id' => $scheduleId,
            'class_id' => $classId,
            'classroom_id' => null,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'day' => $day,
        ]);
        if(!empty($course->CommonLessons)){
            foreach($course->CommonLessons as $lesson){
                $slots [] = ScheduleSlot::create([
                    'schedule_id' => $lesson->scheduleId,
                    'class_id' => $lesson->id,
                    'classroom_id' => null,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'day' => $day,
                ]);
            }
        }
        return ['success' => true, 'slots' => $slots];
    }

    public function addClassroomToSlot($classroomId, $scheduleId, $day, $startTime, $classId, $externalId, $force = false)
    {
        $endTime = date('H:i', strtotime($startTime . ' +45 minute'));

        $validationData = new ScheduleValidationData(
            scheduleId: $scheduleId,
            classId: $classId,
            classroomId: $classroomId,
            instructorId: null,
            day: $day,
            startTime: $startTime,
            endTime: $endTime,
            externalId: $externalId
        );

        $conflicts = $this->detectConflicts($validationData, $this->classroomValidators);
        $slots = ScheduleSlot::query()
            ->where('schedule_id',$scheduleId)
            ->where('class_id',$classId)
            ->where('day',$day)->get();

        $slots = ScheduleSlot::query()->whereHas('courseClass',function($query) use ($externalId,$day){
            $query->where('external_id',$externalId)
            ->where('day',$day);
        })->get();
        if (!empty($conflicts) && !$force) {
            return ['has_conflicts' => true, 'conflicts' => $conflicts];
        }else{
            if($slots){
                foreach($slots as $slot){
                    $slot->classroom_id = $classroomId;
                    $slot->save();
                }
                return ['success' => true, 'slots' => $slots];
            }
        }
        return ['success' => false, 'slot' => $slots];
    }
    private function detectConflicts($validationData,$validators): array
    {
        $conflicts = [];
        foreach ($validators as $validator) {
            $result = $validator->validate($validationData);
            if ($result !== true) {
                $conflicts[$validator->getName()] = $result;
            }
        }
        return $conflicts;
    }


}
