<?php

namespace Micro\Multi\Grid\Actions;

use Micro\Multi\Actions\Response;
use Micro\Multi\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Delete extends RowAction
{
    /**
     * @return array|null|string
     */
    public function name()
    {
        return __('multi.delete');
    }

    /**
     * @param Model $model
     *
     * @return Response
     */
    public function handle(Model $model)
    {
        $trans = [
            'failed'    => trans('multi.delete_failed'),
            'succeeded' => trans('multi.delete_succeeded'),
        ];

        try {
            DB::transaction(function () use ($model) {
                $model->delete();
            });
        } catch (\Exception $exception) {
            return $this->response()->error("{$trans['failed']} : {$exception->getMessage()}");
        }

        return $this->response()->success($trans['succeeded'])->refresh();
    }

    /**
     * @return void
     */
    public function dialog()
    {
        $this->question(trans('multi.delete_confirm'), '', ['confirmButtonColor' => '#d33']);
    }
}
