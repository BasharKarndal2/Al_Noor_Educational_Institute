@props(['id'=>''])
                    
                    
                    
                    
                    <div class="col-md-6">
                            <label for="status" class="form-label">الحالة</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="{{ $id }}" name="status" required>
                                <option value="">اختر...</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                          </div>