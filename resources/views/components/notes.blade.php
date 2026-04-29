   
   @props(['id'=>''])
                    
                    
                    
                    
                  
   
   <div class="col-md-12">
                            <label for="note" class="form-label">ملاحظات</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" id="{{ $id }}"  rows="2" name="note">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>