<?php

namespace App\Models\Etala;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyIncomes extends Model
{
    use HasFactory;

    protected $connection = 'e_tala';
    protected $table = 'family_incomes';

    protected $fillable = [
        'demographic_characteristics_id',
        'geographic_identifications_id',
        'salaries_and_wages',
        'commissions_tips_bonuses_etc',
        'other_forms_of_income',
        'net_receipts_from_business',
        'net_receipts_from_professional_practice',
        'net_receipts_from_farm_and_other_livestock',
        'cash_receipts_from_gifts_and_donations',
        '4Ps_benefits',
        'social_pension_benefits',
        'social_amelioration_benefits',
        'other_cash_receipts_from_gifts_and_donations',
        'dividends_from_investments',
        'rental_income',
        'interest_from_investments',
        'gift_receipts_from_family',
        'family_sustenance_activities',
        'other_sources_of_family_income',
        'total_annual_income_current_family_members',
        'total_annual_income_current_former_family_members',
    ];

    protected $casts = [
        'salaries_and_wages' => 'decimal:2',
        'commissions_tips_bonuses_etc' => 'decimal:2',
        'other_forms_of_income' => 'decimal:2',
        'net_receipts_from_business' => 'decimal:2',
        'net_receipts_from_professional_practice' => 'decimal:2',
        'net_receipts_from_farm_and_other_livestock' => 'decimal:2',
        'cash_receipts_from_gifts_and_donations' => 'decimal:2',
        '4Ps_benefits' => 'decimal:2',
        'social_pension_benefits' => 'decimal:2',
        'social_amelioration_benefits' => 'decimal:2',
        'other_cash_receipts_from_gifts_and_donations' => 'decimal:2',
        'dividends_from_investments' => 'decimal:2',
        'rental_income' => 'decimal:2',
        'interest_from_investments' => 'decimal:2',
        'gift_receipts_from_family' => 'decimal:2',
        'family_sustenance_activities' => 'decimal:2',
        'other_sources_of_family_income' => 'decimal:2',
        'total_annual_income_current_family_members' => 'decimal:2',
        'total_annual_income_current_former_family_members' => 'decimal:2',
    ];

    // Relationships
    public function demographicCharacteristic(): BelongsTo
    {
        return $this->belongsTo(DemographicIdentifications::class, 'demographic_characteristics_id');
    }

    public function geographicIdentification(): BelongsTo
    {
        return $this->belongsTo(GeographicIdentifications::class, 'geographic_identifications_id');
    }

    // Accessors
    public function getTotalIncomeAttribute()
    {
        return $this->total_annual_income_current_family_members + 
               $this->total_annual_income_current_former_family_members;
    }
}
