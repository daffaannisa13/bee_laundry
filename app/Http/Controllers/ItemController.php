<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item; // pastikan ini di-import

class ItemController extends Controller
{
     public function index()
    {
        $items = Item::orderBy('id', 'desc')->paginate(10);
        return view('item.index', compact('items'));
    }

        // Search AJAX
    public function search(Request $request)
    {
        $keyword = $request->input('search','');

        $query = Item::query();
        if($keyword){
            $query->where('nama_service','like',"%{$keyword}%")
                  ->orWhere('tipe_service','like',"%{$keyword}%")
                  ->orWhere('deskripsi','like',"%{$keyword}%");
        }

        $items = $query->orderBy('id','desc')
                       ->paginate(10)
                       ->appends(['search'=>$keyword])
                       ->withPath(route('item.search'));

        // HTML tabel
        $htmlItems = '<div class="orders-table-container">
            <div class="orders-table">
                <div class="table-header">
                    <span>ID</span>
                    <span>Layanan</span>
                    <span>Harga</span>
                    <span>Tipe</span>
                    <span>Deskripsi</span>
                    <span>Action</span>
                </div>';

        foreach($items as $item){
            $htmlItems .= '<div class="table-row">
                <span>#'.$item->id.'</span>
                <span>'.htmlspecialchars($item->nama_service, ENT_QUOTES).'</span>
                <span>Rp '.number_format($item->harga,0,",",".").'</span>
                <span>'.$item->tipe_service.'</span>
                <span>'.htmlspecialchars($item->deskripsi, ENT_QUOTES).'</span>
                <span class="action-buttons">
                    <button class="btn btn-edit" onclick="window.location=\'/item/'.$item->id.'/edit\'">
                        <i class="fa-solid fa-edit"></i>
                    </button>
                    <button class="btn btn-delete" onclick="confirmDelete('.$item->id.')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <form id="delete-form-'.$item->id.'" action="/item/'.$item->id.'" method="POST" style="display:none;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                    </form>
                    <button class="btn btn-detail" onclick="window.location=\'/item/'.$item->id.'\'">
                        <i class="fa-solid fa-info-circle"></i>
                    </button>
                </span>
            </div>';
        }

        $htmlItems .= '</div></div>';

        // Pagination custom
        $pagination = '<ul class="pagination">';

        // Prev
        if($items->onFirstPage()) $pagination .= '<li class="disabled"><a href="#">Prev</a></li>';
        else $pagination .= '<li><a href="'.$items->previousPageUrl().'&search='.$keyword.'">Prev</a></li>';

        // Nomor halaman
        foreach($items->getUrlRange(1,$items->lastPage()) as $page => $url){
            $active = $page == $items->currentPage() ? 'active' : '';
            $pagination .= '<li class="'.$active.'">
                <a href="'.$url.'&search='.$keyword.'">'.$page.'</a>
            </li>';
        }

        // Next
        if($items->hasMorePages()) $pagination .= '<li><a href="'.$items->nextPageUrl().'&search='.$keyword.'">Next</a></li>';
        else $pagination .= '<li class="disabled"><a href="#">Next</a></li>';

        $pagination .= '</ul>';

        return response()->json([
            'data' => $htmlItems,
            'pagination' => $pagination
        ]);
    }

    public function userIndex()
{
    $items = Item::orderBy('id', 'desc')->paginate(12);
    return view('item.user', compact('items'));
}


    public function userSearch(Request $request)
{
    $keyword = $request->search;

    $items = Item::where('nama_service', 'like', "%$keyword%")
    ->orWhere('tipe_service', 'like', "%$keyword%")
    ->orWhere('deskripsi', 'like', "%$keyword%")
    ->paginate(12)
    ->appends(['search' => $keyword]);

    $html = '<div class="item-card-container">';

    foreach ($items as $item) {
        $html .= '
        <div class="item-card">
            <div class="item-card-header">
                <h3>'.$item->nama_service.'</h3>
                <span class="badge">'.$item->tipe_service.'</span>
            </div>
            <div class="item-card-body">
                <p class="price">Rp '.number_format($item->harga,0,",",".").'</p>
                <p class="description">'.($item->deskripsi ?: "-").'</p>
            </div>
        </div>';
    }

    $html .= '</div>';

    $pagination = '<ul class="pagination">';

// Prev
if($items->onFirstPage()) $pagination .= '<li class="disabled"><a href="#">Prev</a></li>';
else $pagination .= '<li><a href="'.$items->previousPageUrl().'">Prev</a></li>';

// Nomor halaman
foreach($items->getUrlRange(1,$items->lastPage()) as $page => $url){
    $active = $page == $items->currentPage() ? 'active' : '';
    $pagination .= '<li class="'.$active.'"><a href="'.$url.'">'.$page.'</a></li>';
}

// Next
if($items->hasMorePages()) $pagination .= '<li><a href="'.$items->nextPageUrl().'">Next</a></li>';
else $pagination .= '<li class="disabled"><a href="#">Next</a></li>';

$pagination .= '</ul>';


   return response()->json([
    'data' => $html,
    'pagination' => $pagination
]);

}

    public function create()
    {
        return view('item.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_service' => 'required',
        'harga' => 'required|numeric',
        'tipe_service' => 'required',
        'deskripsi' => 'nullable'
    ]);

    $item = Item::create($request->all());

    // Cek kalau requestnya AJAX
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil ditambahkan',
            'item_id' => $item->id
        ]);
    }

    // Kalau bukan AJAX, redirect biasa
    return redirect()->route('item.index')
                     ->with('success', 'Layanan berhasil ditambahkan');
}


    public function edit($id)
    {
        $item = Item::findOrFail($id); // Item
        return view('item.edit', compact('item'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'nama_service' => 'required',
        'harga' => 'required|numeric',
        'tipe_service' => 'required',
        'deskripsi' => 'nullable'
    ]);

    $item = Item::findOrFail($id);
    $item->update($request->all());

    // Cek kalau requestnya AJAX
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil diperbarui',
            'item_id' => $item->id
        ]);
    }

    // Kalau bukan AJAX, redirect biasa
    return redirect()->route('item.index')
                     ->with('success', 'Layanan berhasil diperbarui');
}

public function show($id)
{
    // Ambil data item, jika tidak ada akan 404
    $item = Item::findOrFail($id);

    // Kirim ke view detail
    return view('item.show', compact('item'));
}


    public function destroy($id)
    {
        Item::destroy($id);

        return redirect()->route('item.index')
                         ->with('success', 'Layanan berhasil dihapus');
    }
}
