<?php namespace App\Http\Controllers;

use App\Models\Product;
use App\Ninja\Repositories\ProductRepository;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductApiController extends BaseAPIController
{
    protected $productRepo;
    
    protected $entityType = ENTITY_PRODUCT;

    public function __construct(ProductRepository $productRepo)
    {
        parent::__construct();

        $this->productRepo = $productRepo;
    }

    public function index()
    {
        $products = Product::scope()
                        ->withTrashed()
                        ->orderBy('created_at', 'desc');

        return $this->listResponse($products);
    }

    public function store(CreateProductRequest $request)
    {
        $product = $this->productRepo->save($request->input());

        return $this->itemResponse($product);
    }

    public function update(UpdateProductRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }
        
        $data = $request->input();
        $data['public_id'] = $publicId;
        $product = $this->productRepo->save($data);

        return $this->itemResponse($product);
    }

    public function destroy($publicId)
    {
       //stub
    }
}
