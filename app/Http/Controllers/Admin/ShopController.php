<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\{Request, UploadedFile};
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\{Banner, Shop};
class ShopController extends Controller
{

    public function index()
    {
        $shop = Shop::orderBy('id', 'asc')->first();
        // die(json_encode($shop));
        return view('admin.shop.settings', [
            'shop' => $shop,
            'pageTitle' => __('messages.shop_settings'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin', 'active' => false],

                ['label' => __('messages.shop_settings'), 'url' => '', 'active' => true],
            ]
        ]);
    }

    public function update(Request $request)
    {
        $shop = Shop::orderBy('id', 'asc')->firstOrFail();

        $validated = $request->validate([
            'name_shop'      => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'description'    => 'nullable|string',
            'open_shop_time' => 'nullable|string|max:50',
            'close_shop'     => 'nullable|string|max:50',
            'facebook'       => 'nullable|url|max:255',
            'x'              => 'nullable|url|max:255',
            'instagram'      => 'nullable|url|max:255',
            'youtube'        => 'nullable|url|max:255',
            'linkedin'       => 'nullable|url|max:255',
            'logo_shop'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = collect($validated)->except('logo_shop')->all();

        if ($request->hasFile('logo_shop')) {
            $newLogo = $request->file('logo_shop')
                ->store('logo_shops', 'public');

            if ($shop->logo_shop) {
                Storage::disk('public')->delete($shop->logo_shop);
            }

            $data['logo_shop'] = $newLogo;
        }

        $shop->update($data);

        return back()->with(
            'success',
            __('messages.updated_successfully')
        );
    }
    public function banners()
    {
        $banners = Banner::orderBy('id', 'asc')->get();
        // die(json_encode($banners));
        return view('admin.shop.banners', [
            'banners' => $banners,
            'pageTitle' => __('messages.banner_management'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin', 'active' => false],

                ['label' => __('messages.banner_management'), 'url' => '', 'active' => true],
            ]
        ]);
    }

    public function bannersUpdate(Request $request)
    {
        $banners = $request->input('banners', []);

        foreach ($banners as $index => $data) {
            if (!empty($data['_delete']) && $data['_delete'] == 1) {

                if (!empty($data['id'])) {
                    $banner = Banner::find($data['id']);
                    if ($banner) {
                        if ($banner->image) {
                            Storage::delete('public/banners/' . $banner->image);
                        }
                        $banner->delete();
                    }
                }
                continue;
            }
            $banner = Banner::updateOrCreate(
                ['id' => $data['id'] ?? null],
                [
                    'title' => $data['title'] ?? '',
                    'link' => $data['link'] ?? '',
                    'status' => isset($data['status']) ? 1 : 0,
                ]
            );
            if ($request->hasFile("banners.$index.image")) {
                if ($banner->image) {
                    Storage::delete('public/banners/' . $banner->image);
                }
                $file = $request->file("banners.$index.image");
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/banners', $filename);
                $banner->update([
                    'image' => $filename
                ]);
            }
        }

        return redirect()
            ->route('shop.banners')
            ->with('success', 'Shop banners updated successfully.');
    }





}
