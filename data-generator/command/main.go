package main

import (
	"encoding/json"
	"flag"
	"fmt"
	"log"
	"net/http"

	population "github.com/mpyw-yattemita/phperkaigi2023-laravel-newrelic-performance/data-generator"
	"github.com/mpyw-yattemita/phperkaigi2023-laravel-newrelic-performance/data-generator/internal/errctrl"
)

const xlsxURL = "https://www.e-stat.go.jp/stat-search/file-download?statInfId=000032143614&fileKind=0"

var pretty bool

func init() {
	flag.BoolVar(&pretty, "pretty", false, "prints formatted JSON")
}

//go:generate sh -c "go run main.go --pretty > ../../src/database/seeders/population-stats.json"
//go:generate sh -c "go run main.go > ../../src/database/seeders/population-stats.min.json"
func main() {
	flag.Parse()
	if err := run(); err != nil {
		log.Fatalln(err)
	}
}

func run() error {
	resp, err := http.Get(xlsxURL)
	if err != nil {
		return fmt.Errorf("failed to fetch data: %w", err)
	}
	defer errctrl.Ignore(resp.Body.Close)

	stats, err := population.Parse(resp.Body)
	if err != nil {
		return fmt.Errorf("failed to parse data: %w", err)
	}

	statsJson, err := func() ([]byte, error) {
		if pretty {
			return json.MarshalIndent(stats, "", "    ")
		}
		return json.Marshal(stats)
	}()

	if err != nil {
		return fmt.Errorf("failed to marshal stats: %w", err)
	}

	fmt.Println(string(statsJson))
	return nil
}
