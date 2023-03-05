package population

import (
	"errors"
	"fmt"
	"io"
	"regexp"
	"strconv"
	"strings"

	"github.com/mpyw-yattemita/phperkaigi2023-laravel-newrelic-performance/data-generator/internal/errctrl"
	"github.com/mpyw-yattemita/phperkaigi2023-laravel-newrelic-performance/data-generator/internal/typeconv"
	"github.com/xuri/excelize/v2"
)

type Statistic struct {
	Prefectures []Prefecture `json:"prefectures"`
}

type Prefecture struct {
	Name                     string   `json:"name"`
	All                      *int64   `json:"all"`
	Male                     *int64   `json:"male"`
	Female                   *int64   `json:"female"`
	At2015                   *int64   `json:"at_2015"`
	ComparedTo2015           *int64   `json:"compared_to_2015"`
	PercentageComparedTo2015 *float64 `json:"percentage_compared_to_2015"`
	Density                  *float64 `json:"density"`
	AverageAge               *float64 `json:"average_age"`
	MedianAge                *float64 `json:"median_age"`
	Under14                  *int64   `json:"under_14"`
	Under64                  *int64   `json:"under_64"`
	Over65                   *int64   `json:"over_65"`
	PercentageUnder14        *float64 `json:"percentage_under_14"`
	PercentageUnder64        *float64 `json:"percentage_under_64"`
	PercentageOver65         *float64 `json:"percentage_over_65"`
	MaleUnder14              *int64   `json:"male_under_14"`
	MaleUnder64              *int64   `json:"male_under_64"`
	MaleOver65               *int64   `json:"male_over_65"`
	MalePercentageUnder14    *float64 `json:"male_percentage_under_14"`
	MalePercentageUnder64    *float64 `json:"male_percentage_under_64"`
	MalePercentageOver65     *float64 `json:"male_percentage_over_65"`
	FemaleUnder14            *int64   `json:"female_under_14"`
	FemaleUnder64            *int64   `json:"female_under_64"`
	FemaleOver65             *int64   `json:"female_over_65"`
	FemalePercentageUnder14  *float64 `json:"female_percentage_under_14"`
	FemalePercentageUnder64  *float64 `json:"female_percentage_under_64"`
	FemalePercentageOver65   *float64 `json:"female_percentage_over_65"`
	Cities                   []City   `json:"cities,omitempty"`
}

type City struct {
	Name                     string     `json:"name"`
	All                      *int64     `json:"all"`
	Male                     *int64     `json:"male"`
	Female                   *int64     `json:"female"`
	At2015                   *int64     `json:"at_2015"`
	ComparedTo2015           *int64     `json:"compared_to_2015"`
	PercentageComparedTo2015 *float64   `json:"percentage_compared_to_2015"`
	Density                  *float64   `json:"density"`
	AverageAge               *float64   `json:"average_age"`
	MedianAge                *float64   `json:"median_age"`
	Under14                  *int64     `json:"under_14"`
	Under64                  *int64     `json:"under_64"`
	Over65                   *int64     `json:"over_65"`
	PercentageUnder14        *float64   `json:"percentage_under_14"`
	PercentageUnder64        *float64   `json:"percentage_under_64"`
	PercentageOver65         *float64   `json:"percentage_over_65"`
	MaleUnder14              *int64     `json:"male_under_14"`
	MaleUnder64              *int64     `json:"male_under_64"`
	MaleOver65               *int64     `json:"male_over_65"`
	MalePercentageUnder14    *float64   `json:"male_percentage_under_14"`
	MalePercentageUnder64    *float64   `json:"male_percentage_under_64"`
	MalePercentageOver65     *float64   `json:"male_percentage_over_65"`
	FemaleUnder14            *int64     `json:"female_under_14"`
	FemaleUnder64            *int64     `json:"female_under_64"`
	FemaleOver65             *int64     `json:"female_over_65"`
	FemalePercentageUnder14  *float64   `json:"female_percentage_under_14"`
	FemalePercentageUnder64  *float64   `json:"female_percentage_under_64"`
	FemalePercentageOver65   *float64   `json:"female_percentage_over_65"`
	Districts                []District `json:"districts,omitempty"`
}

type District struct {
	Name                     string   `json:"name"`
	All                      *int64   `json:"all"`
	Male                     *int64   `json:"male"`
	Female                   *int64   `json:"female"`
	At2015                   *int64   `json:"at_2015"`
	ComparedTo2015           *int64   `json:"compared_to_2015"`
	PercentageComparedTo2015 *float64 `json:"percentage_compared_to_2015"`
	Density                  *float64 `json:"density"`
	AverageAge               *float64 `json:"average_age"`
	MedianAge                *float64 `json:"median_age"`
	Under14                  *int64   `json:"under_14"`
	Under64                  *int64   `json:"under_64"`
	Over65                   *int64   `json:"over_65"`
	PercentageUnder14        *float64 `json:"percentage_under_14"`
	PercentageUnder64        *float64 `json:"percentage_under_64"`
	PercentageOver65         *float64 `json:"percentage_over_65"`
	MaleUnder14              *int64   `json:"male_under_14"`
	MaleUnder64              *int64   `json:"male_under_64"`
	MaleOver65               *int64   `json:"male_over_65"`
	MalePercentageUnder14    *float64 `json:"male_percentage_under_14"`
	MalePercentageUnder64    *float64 `json:"male_percentage_under_64"`
	MalePercentageOver65     *float64 `json:"male_percentage_over_65"`
	FemaleUnder14            *int64   `json:"female_under_14"`
	FemaleUnder64            *int64   `json:"female_under_64"`
	FemaleOver65             *int64   `json:"female_over_65"`
	FemalePercentageUnder14  *float64 `json:"female_percentage_under_14"`
	FemalePercentageUnder64  *float64 `json:"female_percentage_under_64"`
	FemalePercentageOver65   *float64 `json:"female_percentage_over_65"`
}

type parseState int

const (
	parseStatePref parseState = iota + 1
	parseStateCity
	parseStateDistrict
)

type parsedRow struct {
	prefName                 string
	cityOrDistrictName       string
	all                      *int64
	male                     *int64
	female                   *int64
	at2015                   *int64
	comparedTo2015           *int64
	percentageComparedTo2015 *float64
	density                  *float64
	averageAge               *float64
	medianAge                *float64
	under14                  *int64
	under64                  *int64
	over65                   *int64
	percentageUnder14        *float64
	percentageUnder64        *float64
	percentageOver65         *float64
	maleUnder14              *int64
	maleUnder64              *int64
	maleOver65               *int64
	malePercentageUnder14    *float64
	malePercentageUnder64    *float64
	malePercentageOver65     *float64
	femaleUnder14            *int64
	femaleUnder64            *int64
	femaleOver65             *int64
	femalePercentageUnder14  *float64
	femalePercentageUnder64  *float64
	femalePercentageOver65   *float64
}

func Parse(reader io.Reader) (Statistic, error) {
	r, err := excelize.OpenReader(reader)
	if err != nil {
		return Statistic{}, fmt.Errorf("population.Parse(): failed to open: %w", err)
	}

	sheet := r.GetSheetName(0)
	if sheet == "" {
		return Statistic{}, errors.New("population.Parse(): empty sheet")
	}

	stats := Statistic{}
	state := parseStatePref
	rowIndex := 10

	var currentPref *Prefecture
	var currentCity *City
	var currentRow *parsedRow

	for {
		if currentRow == nil {
			row := parseRow(r, sheet, rowIndex)
			if row.prefName == "" {
				return stats, nil
			}
			currentRow = &row
			rowIndex++
		}

		switch state {
		case parseStateDistrict:
			if !strings.HasPrefix(currentRow.cityOrDistrictName, currentCity.Name) {
				state = parseStateCity
				continue
			}
			currentCity.Districts = append(currentCity.Districts, District{
				Name:                     currentRow.cityOrDistrictName,
				All:                      currentRow.all,
				Male:                     currentRow.male,
				Female:                   currentRow.female,
				At2015:                   currentRow.at2015,
				ComparedTo2015:           currentRow.comparedTo2015,
				PercentageComparedTo2015: currentRow.percentageComparedTo2015,
				Density:                  currentRow.density,
				AverageAge:               currentRow.averageAge,
				MedianAge:                currentRow.medianAge,
				Under14:                  currentRow.under14,
				Under64:                  currentRow.under64,
				Over65:                   currentRow.over65,
				PercentageUnder14:        currentRow.percentageUnder14,
				PercentageUnder64:        currentRow.percentageUnder64,
				PercentageOver65:         currentRow.percentageOver65,
				MaleUnder14:              currentRow.maleUnder14,
				MaleUnder64:              currentRow.maleUnder64,
				MaleOver65:               currentRow.maleOver65,
				MalePercentageUnder14:    currentRow.malePercentageUnder14,
				MalePercentageUnder64:    currentRow.malePercentageUnder64,
				MalePercentageOver65:     currentRow.malePercentageOver65,
				FemaleUnder14:            currentRow.femaleUnder14,
				FemaleUnder64:            currentRow.femaleUnder64,
				FemaleOver65:             currentRow.femaleOver65,
				FemalePercentageUnder14:  currentRow.femalePercentageUnder14,
				FemalePercentageUnder64:  currentRow.femalePercentageUnder64,
				FemalePercentageOver65:   currentRow.femalePercentageOver65,
			})
			currentRow = nil
		case parseStateCity:
			if !strings.HasPrefix(currentRow.prefName, currentPref.Name) {
				state = parseStatePref
				continue
			}
			currentPref.Cities = append(currentPref.Cities, City{
				Name:                     currentRow.cityOrDistrictName,
				All:                      currentRow.all,
				Male:                     currentRow.male,
				Female:                   currentRow.female,
				At2015:                   currentRow.at2015,
				ComparedTo2015:           currentRow.comparedTo2015,
				PercentageComparedTo2015: currentRow.percentageComparedTo2015,
				Density:                  currentRow.density,
				AverageAge:               currentRow.averageAge,
				MedianAge:                currentRow.medianAge,
				Under14:                  currentRow.under14,
				Under64:                  currentRow.under64,
				Over65:                   currentRow.over65,
				PercentageUnder14:        currentRow.percentageUnder14,
				PercentageUnder64:        currentRow.percentageUnder64,
				PercentageOver65:         currentRow.percentageOver65,
				MaleUnder14:              currentRow.maleUnder14,
				MaleUnder64:              currentRow.maleUnder64,
				MaleOver65:               currentRow.maleOver65,
				MalePercentageUnder14:    currentRow.malePercentageUnder14,
				MalePercentageUnder64:    currentRow.malePercentageUnder64,
				MalePercentageOver65:     currentRow.malePercentageOver65,
				FemaleUnder14:            currentRow.femaleUnder14,
				FemaleUnder64:            currentRow.femaleUnder64,
				FemaleOver65:             currentRow.femaleOver65,
				FemalePercentageUnder14:  currentRow.femalePercentageUnder14,
				FemalePercentageUnder64:  currentRow.femalePercentageUnder64,
				FemalePercentageOver65:   currentRow.femalePercentageOver65,
			})
			currentCity = &currentPref.Cities[len(currentPref.Cities)-1]
			currentRow = nil
			state = parseStateDistrict
		case parseStatePref:
			fallthrough
		default:
			stats.Prefectures = append(stats.Prefectures, Prefecture{
				Name:                     currentRow.prefName,
				All:                      currentRow.all,
				Male:                     currentRow.male,
				Female:                   currentRow.female,
				At2015:                   currentRow.at2015,
				ComparedTo2015:           currentRow.comparedTo2015,
				PercentageComparedTo2015: currentRow.percentageComparedTo2015,
				Density:                  currentRow.density,
				AverageAge:               currentRow.averageAge,
				MedianAge:                currentRow.medianAge,
				Under14:                  currentRow.under14,
				Under64:                  currentRow.under64,
				Over65:                   currentRow.over65,
				PercentageUnder14:        currentRow.percentageUnder14,
				PercentageUnder64:        currentRow.percentageUnder64,
				PercentageOver65:         currentRow.percentageOver65,
				MaleUnder14:              currentRow.maleUnder14,
				MaleUnder64:              currentRow.maleUnder64,
				MaleOver65:               currentRow.maleOver65,
				MalePercentageUnder14:    currentRow.malePercentageUnder14,
				MalePercentageUnder64:    currentRow.malePercentageUnder64,
				MalePercentageOver65:     currentRow.malePercentageOver65,
				FemaleUnder14:            currentRow.femaleUnder14,
				FemaleUnder64:            currentRow.femaleUnder64,
				FemaleOver65:             currentRow.femaleOver65,
				FemalePercentageUnder14:  currentRow.femalePercentageUnder14,
				FemalePercentageUnder64:  currentRow.femalePercentageUnder64,
				FemalePercentageOver65:   currentRow.femalePercentageOver65,
			})
			currentPref = &stats.Prefectures[len(stats.Prefectures)-1]
			currentRow = nil
			state = parseStateCity
		}
	}
}

func parseRow(r *excelize.File, sheet string, rowIndex int) parsedRow {
	return parsedRow{
		prefName:                 stripCodePrefix(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("A%d", rowIndex)))),
		cityOrDistrictName:       stripCodePrefix(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("B%d", rowIndex)))),
		all:                      forceIntoInt64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("E%d", rowIndex)))),
		male:                     forceIntoInt64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("F%d", rowIndex)))),
		female:                   forceIntoInt64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("G%d", rowIndex)))),
		at2015:                   forceIntoInt64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("H%d", rowIndex)))),
		comparedTo2015:           forceIntoInt64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("I%d", rowIndex)))),
		percentageComparedTo2015: forceIntoFloat64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("J%d", rowIndex)))),
		density:                  forceIntoFloat64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("L%d", rowIndex)))),
		averageAge:               forceIntoFloat64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("M%d", rowIndex)))),
		medianAge:                forceIntoFloat64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("N%d", rowIndex)))),
		under14:                  forceIntoInt64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("O%d", rowIndex)))),
		under64:                  forceIntoInt64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("P%d", rowIndex)))),
		over65:                   forceIntoInt64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("Q%d", rowIndex)))),
		percentageUnder14:        forceIntoFloat64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("R%d", rowIndex)))),
		percentageUnder64:        forceIntoFloat64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("S%d", rowIndex)))),
		percentageOver65:         forceIntoFloat64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("T%d", rowIndex)))),
		maleUnder14:              forceIntoInt64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("U%d", rowIndex)))),
		maleUnder64:              forceIntoInt64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("V%d", rowIndex)))),
		maleOver65:               forceIntoInt64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("W%d", rowIndex)))),
		malePercentageUnder14:    forceIntoFloat64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("X%d", rowIndex)))),
		malePercentageUnder64:    forceIntoFloat64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("Y%d", rowIndex)))),
		malePercentageOver65:     forceIntoFloat64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("Z%d", rowIndex)))),
		femaleUnder14:            forceIntoInt64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("AA%d", rowIndex)))),
		femaleUnder64:            forceIntoInt64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("AB%d", rowIndex)))),
		femaleOver65:             forceIntoInt64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("AC%d", rowIndex)))),
		femalePercentageUnder14:  forceIntoFloat64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("AD%d", rowIndex)))),
		femalePercentageUnder64:  forceIntoFloat64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("AE%d", rowIndex)))),
		femalePercentageOver65:   forceIntoFloat64(errctrl.Warn(r.GetCellValue(sheet, fmt.Sprintf("AF%d", rowIndex)))),
	}
}

var stripCodePrefixRegex = regexp.MustCompile("^\\d+_")

func stripCodePrefix(s string) string {
	return stripCodePrefixRegex.ReplaceAllString(s, "")
}

func forceIntoInt64(s string) *int64 {
	s = strings.TrimSpace(strings.ReplaceAll(s, ",", ""))
	if s == "" || s == "-" {
		return nil
	}
	return typeconv.Ref(errctrl.Warn(strconv.ParseInt(s, 10, 64)))
}

func forceIntoFloat64(s string) *float64 {
	s = strings.TrimSpace(strings.ReplaceAll(s, ",", ""))
	if s == "" || s == "-" {
		return nil
	}
	return typeconv.Ref(errctrl.Warn(strconv.ParseFloat(s, 64)))
}
