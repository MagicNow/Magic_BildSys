<?php

namespace App\Http\Controllers;

use App\DataTables\TemplateEmailDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTemplateEmailRequest;
use App\Http\Requests\UpdateTemplateEmailRequest;
use App\Repositories\TemplateEmailRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Response;

class TemplateEmailController extends AppBaseController
{
    /** @var  TemplateEmailRepository */
    private $templateEmailRepository;

    public function __construct(TemplateEmailRepository $templateEmailRepo)
    {
        $this->templateEmailRepository = $templateEmailRepo;
    }

    /**
     * Display a listing of the TemplateEmail.
     *
     * @param TemplateEmailDataTable $templateEmailDataTable
     * @return Response
     */
    public function index(TemplateEmailDataTable $templateEmailDataTable)
    {
        return $templateEmailDataTable->render('template_emails.index');
    }

    /**
     * Show the form for creating a new TemplateEmail.
     *
     * @return Response
     */
    public function create()
    {
        return view('template_emails.create');
    }

    /**
     * Store a newly created TemplateEmail in storage.
     *
     * @param CreateTemplateEmailRequest $request
     *
     * @return Response
     */
    public function store(CreateTemplateEmailRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::id();

        $templateEmail = $this->templateEmailRepository->create($input);

        Flash::success('Template Email '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('templateEmails.index'));
    }

    /**
     * Display the specified TemplateEmail.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $templateEmail = $this->templateEmailRepository->findWithoutFail($id);

        if (empty($templateEmail)) {
            Flash::error('Template Email '.trans('common.not-found'));

            return redirect(route('templateEmails.index'));
        }

        return view('template_emails.show')->with('templateEmail', $templateEmail);
    }

    /**
     * Show the form for editing the specified TemplateEmail.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $templateEmail = $this->templateEmailRepository->findWithoutFail($id);

        if (empty($templateEmail)) {
            Flash::error('Template Email '.trans('common.not-found'));

            return redirect(route('templateEmails.index'));
        }

        return view('template_emails.edit')->with('templateEmail', $templateEmail);
    }

    /**
     * Update the specified TemplateEmail in storage.
     *
     * @param  int              $id
     * @param UpdateTemplateEmailRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTemplateEmailRequest $request)
    {
        $templateEmail = $this->templateEmailRepository->findWithoutFail($id);

        if (empty($templateEmail)) {
            Flash::error('Template Email '.trans('common.not-found'));

            return redirect(route('templateEmails.index'));
        }

        $input = $request->all();
        $input['user_id'] = Auth::id();
        $templateEmail = $this->templateEmailRepository->update($input, $id);

        Flash::success('Template Email '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('templateEmails.index'));
    }

    /**
     * Remove the specified TemplateEmail from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $templateEmail = $this->templateEmailRepository->findWithoutFail($id);

        if (empty($templateEmail)) {
            Flash::error('Template Email '.trans('common.not-found'));

            return redirect(route('templateEmails.index'));
        }

        $this->templateEmailRepository->delete($id);

        Flash::success('Template Email '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('templateEmails.index'));
    }
}
