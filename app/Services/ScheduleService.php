<?php

namespace App\Services;

use App\Dto\ScheduleValidationData;
use App\Enums\ConflictType;
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
            return [
                'status' => 'blocked',
                'conflict' =>[
                    'name' => null,
                    'type' => null,
                    'message' => 'Ders zaten planlanmış',
                    'details' => 'Ders zaten planlanmış',
                ]
            ];
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

            if ($conflicts['blocking']) {
                return [
                    'status' => 'blocked',
                    'conflict' => $conflicts['blocking']
                ];
            }
            if (!empty($conflicts['soft'])) {
                return [
                    'status' => 'soft_conflicts',
                    'conflicts' => $conflicts['soft']
                ];
            }
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
        return ['status' => 'success', 'slots' => $slots];
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

        if (!empty($conflicts) && !$force) {
            if ($conflicts['blocking']) {
                return [
                    'status' => 'blocked',
                    'conflict' => $conflicts['blocking']
                ];
            }
            if (!empty($conflicts['soft'])) {
                return [
                    'status' => 'soft_conflicts',
                    'conflicts' => $conflicts['soft']
                ];
            }
        }
        $slots = ScheduleSlot::query()
            ->where('start_time', '>=', $startTime)
            ->whereHas('courseClass', function ($query) use ($externalId, $day) {
                $query->where('external_id', $externalId)
                    ->where('day', $day);
            })->get();
        if ($slots) {
            foreach ($slots as $slot) {
                $slot->classroom_id = $classroomId;
                $slot->save();
            }
        }
        return ['status' => 'success', 'slots' => $slots];
    }


    private function detectConflicts($validationData,$validators): array
    {
        $blocking = null;
        $softConflicts  = [];


        foreach ($validators as $validator) {
            $result = $validator->validate($validationData);
            if ($result !== true) {
                $type = $validator->getAction();
                $conflictItem = [
                    'name' => $validator->getName(),
                    'type' => $type,
                    'message' => $result['message'] ?? 'Çakışma var',
                    'details' => $result['conflicts'] ?? [],
                ];

                if ($type === ConflictType::BLOCKING) {
                    $blocking = $conflictItem;
                    break;
                }

                $softConflicts[] = $conflictItem;
            }
        }
        if (!$blocking && empty($softConflicts)) {
            return [];
        }

        return [
            'blocking' => $blocking,
            'soft' => $softConflicts,
        ];
    }


}
