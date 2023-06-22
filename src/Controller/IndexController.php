<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/index/{name}', name: 'app_index')]
    public function index(Request $request, string $name = ''): Response
    {
//        $request->attributes Get 请求参数
//        $request->query Get 路由参数
//        $request->request Post 请求参数
        return new Response(<<<EOF
<html>
<head>
    <title>My First Page</title>
</head>
<body>
    <p>Hello $name</p>
</body>
</html>
EOF
);
//        return $this->json([
//            'message' => 'Welcome to your new controller!',
//            'path' => 'src/Controller/IndexController.php',
//        ]);
    }
}
