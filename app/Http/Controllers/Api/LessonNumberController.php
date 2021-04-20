<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LessonNumber;
use Illuminate\Http\Request;

class LessonNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lessonNumbers = LessonNumber::getAllLessonNumbersId();

        return response()->json($lessonNumbers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LessonNumber  $lessonNumber
     * @return \Illuminate\Http\Response
     */
    public function show(LessonNumber $lessonNumber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LessonNumber  $lessonNumber
     * @return \Illuminate\Http\Response
     */
    public function edit(LessonNumber $lessonNumber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LessonNumber  $lessonNumber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LessonNumber $lessonNumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LessonNumber  $lessonNumber
     * @return \Illuminate\Http\Response
     */
    public function destroy(LessonNumber $lessonNumber)
    {
        //
    }
}
