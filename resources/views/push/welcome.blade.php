<div style="position: fixed;bottom: 0;right: 20px;width: 30%;z-index: 99;" class="rc-welcome-push">
    <div class="form_inner_div border-radius-reg box_shadow_reg mt-1" style="background: #f7f7f7;margin: 30px 0;border-radius:20px;box-shadow: 0px 4px 10px #5590f529;margin-top: 0.25rem;">
        <div class="body_div" style="padding: 15px;display: flex;">
            <div class="row align-items-center" style="display: flex;flex-wrap: wrap;margin-right: -15px; margin-left: -15px;width: 90%;">
                <div style="flex: 0 0 25%;max-width: 25%;padding-right: 0;position: relative; width: 100%; min-height: 1px;padding-left: 15px;">
                    <div class="off-bg-div" style="text-align: center;">
                        <img src="{{$data['logo']}}" style="width: 100px;"/>
                    </div>
                </div>
                <div style="flex: 0 0 75%;max-width: 75%;position: relative;width: 100%;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                    <div class="content_div">
                        <p style="font-weight: 400;margin-bottom: 0.5rem;">{{$data['headline']}}</p>
                        <p style="font-weight: 400;margin-bottom: 0.5rem;">{{$data['body_text']}}</p>
                        <a href="{{$data['url']}}" target="_blank"><p style="color: #707070;margin-bottom: 0 !important;">{{$data['url']}}</p></a>
                    </div>
                </div>
            </div>
            <div class="close rc-close" style="text-align: end;width: 10%;cursor: pointer;">
                <img src="{{ asset('/images/close.png') }}" style="width: 25px;"/>
            </div>
        </div>
    </div>
</div>
