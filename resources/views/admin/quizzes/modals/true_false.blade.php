<div id="TrueFalseModal" class="{{ empty($question_edit) ? 'd-none' : ''}}">
    <div class="custom-modal-body">
        <h2 class="section-title after-line">True False Question</h2>
        <div class="quiz-questions-form" data-action="/admin/quizzes-questions/{{ empty($question_edit) ? 'store' : $question_edit->id.'/update' }}" method="post">
            <input type="hidden" name="ajax[quiz_id]" value="{{ !empty($quiz) ? $quiz->id :'' }}">
            <input type="hidden" name="ajax[type]" value="{{ \App\Models\QuizzesQuestion::$true_false }}">
            @if(!empty(getGeneralSettings('content_translate')))
                    <div class="col-12">
                        <div class="form-group">
                            <label class="input-label">{{ trans('auth.language') }}</label>
                            <select name="ajax[locale]"
                                    class="form-control {{ !empty($question_edit) ? 'js-quiz-question-locale' : '' }}"
                                    data-id="{{ !empty($question_edit) ? $question_edit->id : '' }}"
                            >
                                @foreach($userLanguages as $lang => $language)
                                    <option value="{{ $lang }}" {{ (!empty($question_edit) and !empty($question_edit->locale)) ? (mb_strtolower($question_edit->locale) == mb_strtolower($lang) ? 'selected' : '') : (app()->getLocale() == $lang ? 'selected' : '') }}>{{ $language }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="ajax[locale]" value="{{ $defaultLocale }}">
                @endif
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label class="input-label">{{ trans('quiz.question_title') }}</label>
                        <input type="text" name="ajax[title]" class="js-ajax-title form-control" value="{{ !empty($question_edit) ? $question_edit->title : '' }}"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="col-12 col-md-4" style="display:none;">
                    <div class="form-group">
                        <label class="input-label">{{ trans('quiz.grade') }}</label>
                        <input type="text" name="ajax[grade]" class="js-ajax-grade form-control" value="{{ !empty($question_edit) ? $question_edit->grade : '1' }}"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="mt-3">
                    <h2 class="section-title after-line">{{ trans('public.answers') }}</h2>
    
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-sm btn-primary mt-2 add-answer-btn">{{ trans('quiz.add_an_answer') }}</button>
    
                        <div class="form-group">
                            <input type="hidden" name="ajax[current_answer]" class="form-control"/>
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                </div>
                <div class="add-answer-container">

                    @if (!empty($question_edit->quizzesQuestionsAnswers) and !$question_edit->quizzesQuestionsAnswers->isEmpty())
                        @foreach ($question_edit->quizzesQuestionsAnswers as $answer)
                            @include('admin.quizzes.modals.multiple_answer_form',['answer' => $answer])
                        @endforeach
                    @else
                        @include('admin.quizzes.modals.multiple_answer_form')
                    @endif
                </div>
                <div class="d-flex align-items-center justify-content-end mt-3">
                    <button type="button" class="save-question btn btn-sm btn-primary">{{ trans('public.save') }}</button>
                    <button type="button" class="close-swl btn btn-sm btn-danger ml-2">{{ trans('public.close') }}</button>
                </div>
        </div>
    </div>
</div>