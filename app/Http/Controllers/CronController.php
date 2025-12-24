<?php

namespace App\Http\Controllers;

use App\Lib\CurlRequest;
use App\Models\CronJob;
use App\Models\CronJobLog;
use Carbon\Carbon;
use Status;

class CronController extends Controller{
    
    protected $limit; 
    protected $billingSetting;
    protected $selectInvoiceColumns;

    public function __construct(){
        $this->limit = 100;
        $this->billingSetting = null; // Removed BillingSetting reference
        $this->selectInvoiceColumns = 'id, reminder, user_id, amount, status, due_date, created, last_cron, hosting_id, domain_id'; 
    }
 
    public function all(){

        $general            = gs();
        $general->last_cron = now();
        $general->save();

        $this->invoiceGenerate(); 
        $this->unpaidInvoiceReminder();
        $this->firstOverdueReminder();
        $this->secondOverdueReminder();
        $this->thirdOverdueReminder();
        $this->addLateFee();
        $this->removeShoppingCarts();

        $notify[] = ['success', 'Manually cron run successfully'];
        return back()->withNotify($notify);
    }

    public function cron()
    {
        $general            = gs();
        $general->last_cron = now();
        $general->save();

        $crons = CronJob::with('schedule');

        if (request()->alias) {
            $crons->where('alias', request()->alias);
        } else {
            $crons->where('next_run', '<', now())->where('is_running', Status::YES);
        }
        $crons = $crons->get();
        foreach ($crons as $cron) {
            $cronLog              = new CronJobLog();
            $cronLog->cron_job_id = $cron->id;
            $cronLog->start_at    = now();
            if ($cron->is_default) {
                $controller = new $cron->action[0];
                try {
                    $method = $cron->action[1];
                    $controller->$method();
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            } else {
                try {
                    CurlRequest::curlContent($cron->url);
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            }
            $cron->last_run = now();
            $cron->next_run = now()->addSeconds($cron->schedule->interval);
            $cron->save();

            $cronLog->end_at = $cron->last_run;

            $startTime         = Carbon::parse($cronLog->start_at);
            $endTime           = Carbon::parse($cronLog->end_at);
            $diffInSeconds     = $startTime->diffInSeconds($endTime);
            $cronLog->duration = $diffInSeconds;
            $cronLog->save();
        }
        if (request()->target == 'all') {
            $notify[] = ['success', 'Cron executed successfully'];
            return back()->withNotify($notify);
        }
        if (request()->alias) {
            $notify[] = ['success', keyToTitle(request()->alias) . ' executed successfully'];
            return back()->withNotify($notify);
        }
    }

    protected function invoiceGenerate(){
        
        // Simplified version - removed all hosting and domain invoice generation logic
        return true;
    }

    protected function generateHostingInvoice($hostings){
        // Method kept for structure but functionality removed
        return true;
    }

    protected function invoiceItemForHosting($hosting, $invoice){
        // Method kept for structure but functionality removed
        return true;
    }

    protected function generateDomainInvoice($domains){
        // Method kept for structure but functionality removed
        return true;
    }

    protected function invoiceItemForDomain($invoice, $domain){
        // Method kept for structure but functionality removed
        return true;
    }

    protected function unpaidInvoiceReminder(){
        // Removed invoice reminder logic
        return true;
    }
    
    protected function firstOverdueReminder(){
        // Removed invoice reminder logic
        return true;
    }

    protected function secondOverdueReminder(){
        // Removed invoice reminder logic
        return true;
    }

    protected function thirdOverdueReminder(){
        // Removed invoice reminder logic
        return true;
    }

    protected function addLateFee(){
        // Removed late fee logic
        return true;
    }

    protected function invoices($column, $days, $addOrLess){
        // Return empty collection since invoice functionality is removed
        return collect();
    }

    protected function hostingNextDueDate($billingCycle, $hosting){
        return null;
    }

    protected function domainNextDueDate($domain){
        return null;
    }

    private function tax($invoice){
        // Tax functionality removed
        return true;
    }

    private function removeShoppingCarts(){
        // Shopping cart removal functionality removed
        return true;
    }

}