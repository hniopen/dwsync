<?php

namespace Hni\Dwsync\Http\Controllers;

use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Hni\Dwsync\Models\DwSms;
use Hni\Dwsync\Repositories\DwSmsRepository;

class DwSmsController extends AppBaseController
{
    /** @var  DwSmsRepository */
    private $dwSmsRepository;

    public function __construct(DwSmsRepository $dwSmsRepo)
    {
        $this->dwSmsRepository = $dwSmsRepo;
    }
    
    /**
     * Test SMS sending
     *
     * @param Request $request
     * @return Response
     */
    public function send(Request $request)
    {
        $num = $request['num'];
        $content = $request['content'];
        $result = DwSms::sendSMS($num, $content);//$num, $content
        return response()->json($result);
    }
}
